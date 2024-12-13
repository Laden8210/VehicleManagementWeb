<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class ReminderCreated extends Notification
{
    use Queueable;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'reminder_id' => $this->reminder->id,
            'message' => 'A new reminder has been set for ' . $this->reminder->ReminderDate . '.',
            'created_at' => now(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'reminder_id' => $this->reminder->id,
            'message' => 'A new reminder has been set for ' . $this->reminder->ReminderDate . '.',
            'created_at' => now(),
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Reminder Created')
            ->line('A new reminder has been set for ' . $this->reminder->ReminderDate . '.')
            ->action('View Reminder', url('/reminders/' . $this->reminder->id))
            ->line('Thank you for using our application!');
    }
}
