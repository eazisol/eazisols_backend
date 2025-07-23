<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first user (assuming it's an HR user)
        $user = User::first();
        
        if (!$user) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        $reminders = [
            [
                'title' => 'Monthly Salary Payment',
                'description' => 'Process and transfer salaries to all employees. Tasks include:
- Verify payroll calculations
- Check bank transfer details
- Process salary transfers
- Send salary slips to employees
- Update payroll records',
                'day_of_month' => 25,
                'time_of_day' => '09:00:00',
            ],
            [
                'title' => 'Monthly Compliance Report',
                'description' => 'Submit monthly compliance report to regulatory authorities. Include:
- Employee attendance records
- Leave management summary
- Safety incident reports
- Training completion status
- Regulatory compliance checklist',
                'day_of_month' => 15,
                'time_of_day' => '14:00:00',
            ],
            [
                'title' => 'Utility Bills Payment',
                'description' => 'Pay monthly utility bills and office expenses:
- Electricity bill
- Internet and phone bills
- Office rent
- Maintenance charges
- Update expense records',
                'day_of_month' => 5,
                'time_of_day' => '11:00:00',
            ],
            [
                'title' => 'Employee Performance Reviews',
                'description' => 'Conduct monthly performance review activities:
- Schedule one-on-one meetings
- Review performance metrics
- Update employee records
- Plan training and development
- Prepare performance reports',
                'day_of_month' => 1,
                'time_of_day' => '10:00:00',
            ],
            [
                'title' => 'Monthly HR Reports',
                'description' => 'Generate and submit monthly HR reports:
- Attendance summary report
- Leave utilization report
- Recruitment status update
- Employee satisfaction metrics
- Budget and expense analysis',
                'day_of_month' => 30,
                'time_of_day' => '16:00:00',
            ],
        ];

        foreach ($reminders as $reminderData) {
            $reminder = new Reminder([
                'user_id' => $user->id,
                'title' => $reminderData['title'],
                'description' => $reminderData['description'],
                'day_of_month' => $reminderData['day_of_month'],
                'time_of_day' => $reminderData['time_of_day'],
            ]);

            // Calculate the next trigger date
            $reminder->next_trigger_at = $reminder->calculateNextTrigger();
            $reminder->save();

            $this->command->info("Created reminder: {$reminder->title}");
        }

        $this->command->info('Reminder seeder completed successfully!');
    }
}