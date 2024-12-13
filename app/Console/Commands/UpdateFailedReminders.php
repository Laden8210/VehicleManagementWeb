<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;

class UpdateFailedReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:update-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update reminders to failed status if not acknowledged by due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get current date
        $currentDate = now();

        // Find reminders that are overdue and have not been acknowledged or marked as action taken
        $reminders = Reminder::where('ReminderStatus', 'Pending')
            ->where(function ($query) use ($currentDate) {
                $query->where('ReminderDate', '<', $currentDate)
                    ->orWhere('DueDate', '<', $currentDate);
            })->get();

        foreach ($reminders as $reminder) {
            // Update status to failed
            $reminder->update(['ReminderStatus' => 'Failed']);
        }

        $this->info('Overdue reminders updated to failed status.');
    }
}
