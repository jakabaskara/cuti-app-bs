<?php

namespace App\Http\Controllers;

use App\Notifications\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

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
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($response);
    }

    public function commandHandlerWebhook()
    {
        $updates = Telegram::getWebhookUpdates();
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $telegram = new Api($botToken);
        $update = $telegram->getWebhookUpdate();


        // dd($updates);

        // if ($updates->getMessage() !== null) {
        //     $chat_id = $updates->getMessage()->getChat()->getId();
        //     $username = $updates->getMessage()->getChat()->getUsername();
        //     $text = $updates->getMessage()->getText();

        //     if (strtolower($text) === 'halo') {

        //         $reply_markup = Keyboard::make()
        //             ->setResizeKeyboard(true)
        //             ->setOneTimeKeyboard(true)
        //             ->row([
        //                 Keyboard::button('1'),
        //                 Keyboard::button('2'),
        //                 Keyboard::button('3'),
        //             ])
        //             ->row([
        //                 Keyboard::button('4'),
        //                 Keyboard::button('5'),
        //                 Keyboard::button('6'),
        //             ])
        //             ->row([
        //                 Keyboard::button('7'),
        //                 Keyboard::button('8'),
        //                 Keyboard::button('9'),
        //             ])
        //             ->row([
        //                 Keyboard::button('0'),
        //             ]);

        //         Telegram::sendMessage([
        //             'chat_id' => $chat_id,
        //             'text' => 'Halo ' . $updates->getMessage(),
        //             'reply_markup' => json_encode([
        //                 'inline_keyboard' => [
        //                     [
        //                         ['text' => 'Tombol 1', 'callback_data' => 'tombol1'],
        //                         ['text' => 'Tombol 2', 'callback_data' => 'tombol2']
        //                     ],
        //                 ],
        //                 'one_time_keyboard' => true,
        //             ])
        //         ]);
        //     }



        //     if (strtolower($text) === 'sisa cuti') {
        //         Telegram::sendMessage([
        //             'chat_id' => $chat_id,
        //             'text' => 'Daftar Sisa Cuti',
        //         ]);
        //     }
        // }

        Telegram::answerCallbackQuery([
            'callback_query_id' => $updates->callbackQuery->get('id'),
            'text'  => 'Halo',
        ]);

        // Di dalam commandHandlerWebhook()

        // if ($updates->isType('callback_query')) {
        //     $callbackQuery = $updates->getCallbackQuery();
        //     $data = $callbackQuery->getData();
        //     $chatId = $callbackQuery->getMessage()->getChat()->getId();

        //     // Periksa apakah ID chat pengguna yang melakukan tindakan sama dengan ID yang diizinkan
        //     if ($chatId == '1176854977') {
        //         // Lakukan tindakan hanya jika ID chat pengguna yang sesuai dengan yang diizinkan
        //         $permintaanCutiId = $data;
        //         $permintaanCuti = PermintaanCuti::findOrFail($permintaanCutiId);

        //         // Periksa apakah tombol "Setujui" atau "Tolak" yang ditekan
        //         if ($callbackQuery->getData() == 'setujui') {
        //             $permintaanCuti->is_approved = true;
        //             $permintaanCuti->save();

        //             // Kirim pesan konfirmasi ke pengguna
        //             Telegram::sendMessage([
        //                 'chat_id' => $chatId,
        //                 'text' => 'Permintaan cuti telah disetujui.'
        //             ]);
        //         } elseif ($callbackQuery->getData() == 'tolak') {
        //             $permintaanCuti->is_rejected = true;
        //             $permintaanCuti->save();

        //             // Kirim pesan konfirmasi ke pengguna
        //             Telegram::sendMessage([
        //                 'chat_id' => $chatId,
        //                 'text' => 'Permintaan cuti telah ditolak.'
        //             ]);
        //         }
        //     } else {
        //         // Jika ID chat tidak diizinkan, kirim pesan bahwa pengguna tidak diizinkan
        //         Telegram::sendMessage([
        //             'chat_id' => $chatId,
        //             'text' => 'Maaf, Anda tidak diizinkan untuk melakukan tindakan ini.'
        //         ]);
        //     }
        // }

        // Inisialisasi bot API


        // Pastikan update yang diterima adalah callback query
        if ($update->isType('callback_query')) {
            $callbackQuery = $update->getCallbackQuery();

            // Mendapatkan data dari callback query
            $callbackData = $callbackQuery->getData();
            $data = $callbackQuery->getData();


            // Mendapatkan ID chat
            $chatId = $callbackQuery->getMessage()->getChat()->getId();

            // Balas callback query sesuai dengan data yang diterima
            if ($callbackData === 'setujui') {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Anda menyetujui permintaan cuti.' . $data,
                ]);
            } elseif ($callbackData === 'tolak') {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Anda menolak permintaan cuti.',
                ]);
            }
        }
    }

    public function getSisaCutiBot()
    {
        $updates = Telegram::getWebhookUpdates();
        if ($updates->getMessage() !== null) {
        }
    }
}
