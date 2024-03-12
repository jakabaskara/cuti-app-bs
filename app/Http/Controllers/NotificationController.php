<?php

namespace App\Http\Controllers;

use App\Notifications\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Telegram\Bot\Laravel\Facades\Telegram;

class NotificationController extends Controller
{

    public function sendNotification()
    {
        $response = Telegram::bot('present-notification')->getMe();
        dd($response->id);
        $user = Auth::user();
        $message = 'Kirimkan saya chat id';
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Setujui', 'callback_data' => 'tombol1_data'],
                    ['text' => 'Tolak', 'callback_data' => 'tombol2_data']
                ]
            ]
        ];

        $pesan = 'Apakah Cuti Disetujui?';

        // Mengonversi keyboard menjadi JSON
        $keyboard = json_encode($keyboard);

        // Kirim pesan dengan keyboard inline
        $response = file_get_contents("https://api.telegram.org/bot7168138742:AAH7Nlo0YsgvIl4S-DexMsWK34_SOAocfqI/sendMessage?chat_id=1176854977&text=$message&reply_markup=$keyboard");

        return redirect()->back();
    }

    public function replyNotification($chatId)
    {
        $message = 'Notification Send Back';

        $response = file_get_contents("https://api.telegram.org/bot7168138742:AAH7Nlo0YsgvIl4S-DexMsWK34_SOAocfqI/sendMessage?chat_id=1176854977&text=$message");
    }

    public function webhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        if ($update->isType('command') && $update->getCommand() === 'halo') {
            $chatId = $update->getMessage()->getChat()->getId();
            $response = "Selamat datang di bot ini! Terima kasih telah memulai.";
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $response
            ]);
        }

        return 'OK';
    }

    public function setWebhook()
    {
        $response = Telegram::setWebHook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($response);
    }

    public function commandHandlerWebhook()
    {
        $updates = Telegram::commandsHandler(true);
    }
}
