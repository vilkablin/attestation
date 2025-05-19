<?php

namespace App\Services;

use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\User;

class TelegramNotifier
{
  public static function sendToUser(User $user, string $message): void
  {
    if (!$user->telegram_id) {
      return;
    }

    Telegram::bot()->sendMessage([
      'chat_id' => $user->telegram_id,
      'text' => $message,
      'parse_mode' => 'Markdown',
    ]);
  }

  public static function sendToAll(string $message): void
  {
    User::whereNotNull('telegram_id')->chunk(100, function ($users) use ($message) {
      foreach ($users as $user) {
        self::sendToUser($user, $message);
      }
    });
  }
}
