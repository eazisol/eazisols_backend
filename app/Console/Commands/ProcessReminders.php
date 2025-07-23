<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Helpers\MailHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process due reminders and send email notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing reminders...');

        // Get all due reminders
        $dueReminders = Reminder::due()->with('user')->get();

        if ($dueReminders->isEmpty()) {
            $this->info('No due reminders found.');
            return 0;
        }

        $processed = 0;
        $failed = 0;

        foreach ($dueReminders as $reminder) {
            try {
                $this->info("Processing reminder: {$reminder->title} for {$reminder->user->name}");

                // Send email notification
                MailHelper::send(
                    $reminder->user->email,
                    $reminder->user->name,
                    $reminder->title,
                    'emails.reminder',
                    [
                        'reminder' => $reminder,
                        'user' => $reminder->user,
                        'isTest' => false
                    ]
                );

                // Mark as notified and schedule next occurrence
                $reminder->markAsNotified();

                $this->info("Reminder sent successfully to {$reminder->user->email}");
                $this->info("  Next occurrence: {$reminder->next_trigger_at->format('Y-m-d H:i:s')}");
                
                $processed++;

                Log::info('Reminder processed successfully', [
                    'reminder_id' => $reminder->id,
                    'user_id' => $reminder->user_id,
                    'title' => $reminder->title,
                    'next_trigger' => $reminder->next_trigger_at
                ]);

            } catch (\Exception $e) {
                $this->error("Failed to process reminder {$reminder->id}: {$e->getMessage()}");
                
                Log::error('Failed to process reminder', [
                    'reminder_id' => $reminder->id,
                    'user_id' => $reminder->user_id,
                    'title' => $reminder->title,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                $failed++;
            }
        }

        $this->info("\nProcessing complete:");
        $this->info("Processed: {$processed}");
        if ($failed > 0) {
            $this->error("Failed: {$failed}");
        }

        return 0;
    }
}