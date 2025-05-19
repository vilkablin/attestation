<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BotSession;
use App\Models\Service;
use App\Models\LocationPoint;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Carbon\Carbon;

class BotController extends Controller
{
    public function webhook(Request $request)
    {
        $message = $request->input('message');
        if (!$message || !isset($message['chat'])) {
            return response()->json();
        }

        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        $session = BotSession::firstOrCreate(['telegram_id' => $chatId]);

        if (!$session->step) {
            $session->update(['step' => 'start']);
        }

        if ($text === '/start') {
            $session->update([
                'step' => 'start',
                'data' => [],
            ]);
        }

        $data = $session->data ?? [];

        switch ($session->step) {
            case 'start':
                $services = Service::all();
                $buttons = array_map(fn($s) => [$s->name], $services->all());
                $session->update([
                    'step' => 'choose_service',
                    'data' => ['services' => $services->pluck('id', 'name')->toArray()]
                ]);

                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Выберите услугу:',
                    'reply_markup' => json_encode([
                        'keyboard' => $buttons,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);
                break;

            case 'choose_service':
                $services = collect($data['services'] ?? []);
                $serviceId = $services[$text] ?? null;

                if (!$serviceId) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Пожалуйста, выберите услугу из списка.'
                    ]);
                    break;
                }

                $locations = LocationPoint::all();
                $buttons = array_map(fn($l) => [$l->address], $locations->all());

                $data['service_id'] = $serviceId;
                $data['locations'] = $locations->pluck('id', 'address')->toArray();
                $session->update(['step' => 'choose_location', 'data' => $data]);

                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Выберите локацию:',
                    'reply_markup' => json_encode([
                        'keyboard' => $buttons,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);
                break;

            case 'choose_location':
                $locations = collect($data['locations'] ?? []);
                $locationId = $locations[$text] ?? null;

                if (!$locationId) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Пожалуйста, выберите локацию из списка.'
                    ]);
                    break;
                }

                $data['location_id'] = $locationId;
                $session->update(['step' => 'choose_date', 'data' => $data]);

                // Получаем доступные даты (например, на 2 недели вперед)
                $availableDates = $this->getAvailableDates($locationId, $data['service_id']);

                $buttons = array_map(fn($date) => [$date], $availableDates);


                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Выберите дату записи:',
                    'reply_markup' => json_encode([
                        'keyboard' => $buttons,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);
                break;

            case 'choose_date':
                // Проверяем, что данные локации и сервиса существуют
                if (!isset($data['location_id']) || !isset($data['service_id'])) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Произошла ошибка. Пожалуйста, начните запись заново (/start)'
                    ]);
                    break;
                }

                // Улучшенная проверка формата даты
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $text)) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Неверный формат даты. Используйте строго ГГГГ-ММ-ДД (например: 2025-05-18).',
                        'parse_mode' => 'Markdown'
                    ]);
                    break;
                }

                // Разбираем дату на компоненты для дополнительной проверки
                list($year, $month, $day) = explode('-', $text);

                // Проверяем валидность даты
                if (!checkdate($month, $day, $year)) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Такой даты не существует. Пожалуйста, введите корректную дату (например: 2025-05-18).',
                        'parse_mode' => 'Markdown'
                    ]);
                    break;
                }

                // Проверяем, что дата не в прошлом
                $inputDate = Carbon::createFromFormat('Y-m-d', $text);
                if ($inputDate->isPast()) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Нельзя выбрать прошедшую дату. Пожалуйста, выберите дату в будущем.',
                        'parse_mode' => 'Markdown'
                    ]);
                    break;
                }

                // Получаем доступные даты
                $availableDates = $this->getAvailableDates($data['location_id'], $data['service_id']);
                if (empty($availableDates) || !in_array($text, $availableDates)) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Выбранная дата недоступна. Пожалуйста, выберите другую дату.'
                    ]);
                    break;
                }

                $data['date'] = $text; // Сохраняем уже проверенную дату
                $session->update(['step' => 'choose_time', 'data' => $data]);

                // Получаем доступное время с проверкой
                $availableHours = $this->getAvailableHours($data['location_id'], $data['service_id'], $data['date']);
                if (empty($availableHours)) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'На выбранную дату нет доступного времени. Пожалуйста, выберите другую дату.'
                    ]);
                    break;
                }

                $buttons = array_map(fn($time) => [$time], $availableHours);
                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Выберите время записи:',
                    'reply_markup' => json_encode([
                        'keyboard' => $buttons,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);
                break;

            case 'choose_time':
                if (!preg_match('/^\d{2}:\d{2}$/', $text)) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Неверный формат времени. Используйте ЧЧ:ММ.'
                    ]);
                    break;
                }

                // Проверяем, что время доступно
                $availableHours = $this->getAvailableHours($data['location_id'], $data['service_id'], $data['date']);
                if (!in_array($text, $availableHours)) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Выбранное время недоступно. Пожалуйста, выберите другое время.'
                    ]);
                    break;
                }

                $datetime = $data['date'] . ' ' . $text;

                $isTaken = Appointment::where('service_id', $data['service_id'])
                    ->where('location_id', $data['location_id'])
                    ->where('date', $datetime)
                    ->exists();

                if ($isTaken) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Это время уже занято. Пожалуйста, выберите другое.'
                    ]);
                    break;
                }

                $data['time'] = $text;
                $session->update(['step' => 'phone', 'data' => $data]);

                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Введите номер телефона:'
                ]);
                break;

            case 'phone':
                $phone = $text;
                $user = User::where('phone', $phone)->first();

                if (!$user) {
                    // Если пользователя нет - запрашиваем пароль
                    $session->update([
                        'step' => 'register_password',
                        'data' => array_merge($data, ['phone' => $phone])
                    ]);

                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'У вас еще нет аккаунта. Введите пароль для регистрации:',
                        'parse_mode' => 'Markdown'
                    ]);
                    break;
                }

                if ($user->telegram_chat_id !== $chatId) {
                    $user->telegram_chat_id = $chatId;
                    $user->save();
                }

                // Если пользователь есть, но еще не получал промокод через бота
                if (!$user->promoCodes()->exists()) {
                    $this->createWelcomePromoCode($user, $chatId);
                }

                $data['user_id'] = $user->id;
                $session->update(['step' => 'promo', 'data' => $data]);

                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Если у вас есть промокод — введите его. Если нет — отправьте "-"'
                ]);
                break;

            case 'register_password':
                if (strlen($text) < 6) {
                    Telegram::bot()->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Пароль должен содержать минимум 6 символов. Попробуйте еще раз:'
                    ]);
                    break;
                }

                // Создаем пользователя
                $user = User::create([
                    'name' => 'Telegram User',
                    'phone' => $data['phone'],
                    'password' => bcrypt($text),
                    'telegram_chat_id' => $chatId,
                ]);

                // Создаем промокод
                $this->createWelcomePromoCode($user, $chatId);

                $data['user_id'] = $user->id;
                $session->update(['step' => 'promo', 'data' => $data]);

                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Если у вас есть промокод — введите его. Если нет — отправьте "-"'
                ]);
                break;

            case 'promo':
                $promoText = trim($text);
                $promoId = null;
                $price = Service::find($data['service_id'])->base_price;

                if ($promoText !== '-') {
                    $promo = PromoCode::where('code', $promoText)
                        ->valid()
                        ->first();

                    if (!$promo) {
                        Telegram::bot()->sendMessage([
                            'chat_id' => $chatId,
                            'text' => 'Неверный или неактивный промокод. Попробуйте ещё раз или отправьте "-"'
                        ]);
                        break;
                    }
                    $price = $price * (1 - ($promo->discount / 100));
                    $promoId = $promo->id;
                }

                Appointment::create([
                    'service_id' => $data['service_id'],
                    'location_id' => $data['location_id'],
                    'date' => $data['date'] . ' ' . $data['time'],
                    'user_id' => $data['user_id'],
                    'price' => $price,
                    'promo_code_id' => $promoId,
                    'status_id' => 1,
                ]);

                $session->delete();

                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Запись подтверждена на *{$data['date']} в {$data['time']}*.\nСпасибо!",
                    'parse_mode' => 'Markdown'
                ]);
                break;

            default:
                Telegram::bot()->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Напишите /start для начала записи.'
                ]);
                break;
        }

        return response()->json();
    }
    protected function getAvailableDates($locationId, $serviceId)
    {
        try {
            $location = LocationPoint::with(['employees' => function ($query) {
                $query->whereNotNull('work_schedule');
            }])->find($locationId);

            if (!$location) {
                return [];
            }

            $availableDates = [];
            $daysToShow = 14;

            for ($i = 0; $i < $daysToShow; $i++) {
                $date = Carbon::today()->addDays($i);

                foreach ($location->employees as $employee) {
                    // Дополнительная проверка на наличие расписания
                    if (empty($employee->work_schedule)) {
                        continue;
                    }

                    try {
                        if ($employee->isWorkingDay($date)) {
                            $availableDates[] = $date->format('Y-m-d');
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            return array_unique($availableDates);
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getAvailableHours($locationId, $serviceId, $date)
    {
        try {
            $location = LocationPoint::with(['employees' => function ($query) use ($serviceId) {
                $query->whereHas('services', function ($q) use ($serviceId) {
                    $q->where('services.id', $serviceId);
                })->whereNotNull('work_schedule');
            }])->find($locationId);

            if (!$location) {
                return [];
            }

            $dateObj = Carbon::parse($date);
            $availableHours = [];

            foreach ($location->employees as $employee) {
                // Проверка на корректность расписания
                if (empty($employee->work_schedule) || !is_array($employee->work_schedule)) {
                    continue;
                }

                try {
                    if (!$employee->isWorkingDay($dateObj)) {
                        continue;
                    }

                    $hours = $employee->availableHoursForDate($dateObj, Service::find($serviceId)->base_time);
                    if (is_array($hours)) {
                        $availableHours = array_merge($availableHours, $hours);
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            $bookedHours = Appointment::where('service_id', $serviceId)
                ->where('location_id', $locationId)
                ->whereDate('date', $date)
                ->pluck('date')
                ->map(function ($datetime) {
                    try {
                        return Carbon::parse($datetime)->format('H:i');
                    } catch (\Exception $e) {
                        return null;
                    }
                })
                ->filter()
                ->toArray();

            return array_values(array_diff($availableHours, $bookedHours));
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function createWelcomePromoCode(User $user, $chatId)
    {
        $code = strtoupper('BOTPROMO' . rand(1000, 9999));

        PromoCode::create([
            'code' => $code,
            'discount' => 10,
            'valid_from' => now(),
            'valid_to' => now()->addDays(30),
            'usage_limit' => 1,
            'user_id' => $user->id,
            'status_id' => 1,
        ]);

        Telegram::bot()->sendMessage([
            'chat_id' => $chatId,
            'text' => "Добро пожаловать! Ваш промокод на скидку 10%: *{$code}*\nМожете использовать его сейчас или позже.",
            'parse_mode' => 'Markdown'
        ]);
    }
}
