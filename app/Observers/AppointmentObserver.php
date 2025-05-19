<?php

namespace App\Observers;

use App\Models\Appointment;
use Telegram\Bot\Laravel\Facades\Telegram;

class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        $user = $appointment->user;
        if (!$user || !$user->telegram_chat_id) {
            return;
        }

        $message = "✅ Ваша запись создана:\n\n"
            . "🧽 Услуга: {$appointment->service->name}\n"
            . "📍 Локация: {$appointment->location->name}\n"
            . "📅 Дата: {$appointment->date->format('d.m.Y H:i')}\n"
            . "💰 Цена: {$appointment->price}₽";

        Telegram::sendMessage([
            'chat_id' => $user->telegram_chat_id,
            'text' => $message,
        ]);
    }

    public function updated(Appointment $appointment): void
    {
        $originalStatus = $appointment->getOriginal('status_id');
        if ($originalStatus !== $appointment->status_id) {
            $user = $appointment->user;
            if (!$user || !$user->telegram_chat_id) {
                return;
            }

            $newStatus = $appointment->status->name;

            $message = "🔄 Статус вашей записи изменился на: *{$newStatus}*";

            Telegram::sendMessage([
                'chat_id' => $user->telegram_chat_id,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        }
    }

    public function deleted(Appointment $appointment): void
    {
        $user = $appointment->user;
        if (!$user || !$user->telegram_chat_id) {
            return;
        }

        $message = "❌ Ваша запись была отменена.";

        Telegram::sendMessage([
            'chat_id' => $user->telegram_chat_id,
            'text' => $message,
        ]);
    }
}
