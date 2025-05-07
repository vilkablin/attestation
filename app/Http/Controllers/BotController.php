<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BotSession;
use App\Models\Service;
use App\Models\LocationPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class BotController extends Controller
{
    public function webhook(Request $request)
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $message = $request->input('message');
        if (!$message || !isset($message['chat'])) return;

        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Найти или создать сессию
        $session = BotSession::firstOrCreate(['telegram_id' => $chatId]);

        Log::info('Telegram update:', $request->all());


        if (!$session->step) {
            $session->update(['step' => 'start']);
        }


        if ($text === '/start') {
            $session->update([
                'step' => 'start',
                'data' => [],
            ]);
        }

        switch ($session->step) {
            case 'start':
                $session->update(['step' => 'choose_service']);
                $services = Service::all();
                $buttons = array_map(fn($s) => [$s->name], $services->all());
                $session->data = ['services' => $services->pluck('id', 'name')];
                $session->save();
                return $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Выберите услугу:',
                    'reply_markup' => json_encode([
                        'keyboard' => $buttons,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);

            case 'choose_service':
                $services = collect($session->data['services']);
                $serviceId = $services[$text] ?? null;
                if (!$serviceId) {
                    return $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Пожалуйста, выберите услугу из списка.'
                    ]);
                }

                $session->data['service_id'] = $serviceId;
                $session->update(['step' => 'choose_date', 'data' => $session->data]);
                return $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Введите дату записи (в формате ГГГГ-ММ-ДД):'
                ]);

            case 'choose_date':
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $text)) {
                    return $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Неверный формат даты. Используйте ГГГГ-ММ-ДД.'
                    ]);
                }

                $session->data['date'] = $text;
                $session->update(['step' => 'choose_time', 'data' => $session->data]);
                return $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Введите время записи (в формате ЧЧ:ММ):'
                ]);

            case 'choose_time':
                if (!preg_match('/^\d{2}:\d{2}$/', $text)) {
                    return $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Неверный формат времени. Используйте ЧЧ:ММ.'
                    ]);
                }

                $session->data['time'] = $text;
                $session->update(['step' => 'phone', 'data' => $session->data]);
                return $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Введите номер телефона:'
                ]);

            case 'phone':
                $session->data['phone'] = $text;

                // Создание записи
                $dateTime = $session->data['date'] . ' ' . $session->data['time'];
                Appointment::create([
                    'service_id' => $session->data['service_id'],
                    'date' => $dateTime,
                    'phone' => $session->data['phone'],
                    'status_id' => 1,
                ]);

                $session->delete();

                return $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Вы успешно записаны!'
                ]);
        }

        return $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Напишите /start для начала записи.'
        ]);
    }
}
