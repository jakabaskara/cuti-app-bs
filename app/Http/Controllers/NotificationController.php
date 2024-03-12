<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class NotificationController extends Controller
{

    public function sendNotification()
    {
        $user = Auth::user();
        $message = 'Notification Send Back';
        Notification::send($user, new SendNotification($message));
    }
}
