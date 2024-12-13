<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function acknowledge($id)
    {
        $notification = DatabaseNotification::find($id);
        $notification->markAsRead();

        return back()->with('success', 'Reminder acknowledged.');
    }
}