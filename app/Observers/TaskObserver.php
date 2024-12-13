<?php

namespace App\Observers;

use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Illuminate\Support\Facades\Log;


class TaskObserver
{
    public function created(Reminder $reminder): void
    {

    }

}
