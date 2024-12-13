<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reminder;

    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Reminder Acknowledged',
            'message' => 'A new reminder has been set for you.',
            'reminder_id' => $this->reminder->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Reminder Set')
            ->line('A new reminder has been set for you.')
            ->action('View Reminder', url('/reminders/' . $this->reminder->id))
            ->line('Thank you for using our application!');
    }
}
