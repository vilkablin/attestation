<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $telegram = new Api();

        $update = $telegram->getWebhookUpdate();
        $chatId = $update->getMessage()->getChat()->getId();
        $text = $update->getMessage()->getText();

        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Вы написали: $text"
        ]);

        return response('OK', 200);
    }
}
