# Monthly Recurring Reminder System - Setup Guide

## Overview
This system allows HR users to create monthly recurring reminders that automatically send email notifications on specified dates and times each month.

## Features
- ✅ Create monthly recurring reminders with custom titles and descriptions
- ✅ Set specific day of month (1-31) and time for reminders
- ✅ Automatic email notifications using existing email system
- ✅ Auto-scheduling for next month after sending
- ✅ Test reminder functionality
- ✅ Activate/deactivate reminders
- ✅ Full CRUD operations with proper authorization
- ✅ Responsive web interface

## Installation Steps

### 1. Database Migration
Run the migration to create the reminders table:
```bash
php artisan migrate
```

### 2. Schedule Configuration
The system uses Laravel's task scheduler to process reminders every 5 minutes. Add this to your server's crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Manual Command Testing
You can manually test the reminder processing:
```bash
php artisan reminders:process
```

## Database Schema

### Reminders Table
```sql
CREATE TABLE reminders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    day_of_month INT NOT NULL,           -- 1-31
    time_of_day TIME NOT NULL,           -- e.g., 09:00:00
    next_trigger_at DATETIME NOT NULL,   -- Next scheduled datetime
    notified BOOLEAN DEFAULT FALSE,      -- Email sent status
    is_active BOOLEAN DEFAULT TRUE,      -- Active/inactive status
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_reminders_trigger (next_trigger_at, notified, is_active)
);
```

## How It Works

### 1. Reminder Creation
- User creates a reminder with title, description, day of month, and time
- System calculates the next trigger datetime
- If the day has already passed this month, it schedules for next month
- Handles month-end edge cases (e.g., Feb 30th becomes Feb 28th)

### 2. Processing Logic
Every 5 minutes, the system:
1. Finds reminders where `next_trigger_at <= now AND notified = false AND is_active = true`
2. Sends email using existing `MailHelper::send()` method
3. Marks reminder as `notified = true`
4. Calculates next month's trigger date
5. Resets `notified = false` for next cycle

### 3. Email Template
Uses `resources/views/emails/reminder.blade.php` with:
- Professional HTML layout
- Reminder details display
- Next occurrence information
- Test email indicators

## API Endpoints

### Web Routes (Protected by Auth Middleware)
```php
GET    /reminders              # List all reminders
GET    /reminders/create       # Show create form
POST   /reminders              # Store new reminder
GET    /reminders/{id}         # Show reminder details
GET    /reminders/{id}/edit    # Show edit form
PUT    /reminders/{id}         # Update reminder
PATCH  /reminders/{id}/toggle-status  # Toggle active status
POST   /reminders/{id}/test    # Send test email
DELETE /reminders/{id}         # Delete reminder
```

### Authorization
- Users can only manage their own reminders
- Uses `ReminderPolicy` for authorization
- Integrated with existing permission system

## File Structure

```
app/
├── Console/Commands/ProcessReminders.php    # Artisan command
├── Http/Controllers/ReminderController.php  # Web controller
├── Models/Reminder.php                      # Eloquent model
├── Policies/ReminderPolicy.php             # Authorization policy
└── Providers/AuthServiceProvider.php       # Policy registration

database/migrations/
└── 2025_07_15_101130_create_reminders_table.php

resources/views/
├── emails/reminder.blade.php               # Email template
└── reminders/
    ├── index.blade.php                     # List view
    ├── create.blade.php                    # Create form
    ├── edit.blade.php                      # Edit form
    └── show.blade.php                      # Detail view

routes/
└── reminders.php                           # Route definitions
```

## Usage Examples

### Creating a Salary Reminder
```
Title: Monthly Salary Payment
Description: Process and transfer salaries to all employees. Verify payroll calculations and bank transfer details.
Day: 25th of every month
Time: 9:00 AM
```

### Creating a Compliance Reminder
```
Title: Monthly Compliance Report
Description: Submit monthly compliance report to regulatory authorities. Include attendance, leave records, and safety metrics.
Day: 15th of every month
Time: 2:00 PM
```

## Testing

### Test Email Functionality
1. Create a reminder
2. Click "Send Test Email" button
3. Check email delivery
4. Verify email formatting and content

### Test Automatic Processing
1. Create a reminder with next trigger in near future
2. Wait for scheduled processing (or run manually)
3. Verify email is sent and next occurrence is scheduled

## Troubleshooting

### Common Issues

1. **Emails not sending**
   - Check email settings in Settings > Email Settings
   - Test email configuration
   - Check Laravel logs for errors

2. **Reminders not processing**
   - Verify cron job is running
   - Check `next_trigger_at` values in database
   - Run `php artisan reminders:process` manually

3. **Wrong next occurrence dates**
   - Check timezone settings
   - Verify month-end handling for dates like 31st

### Logs
- Email sending: Check Laravel logs
- Reminder processing: Command outputs to logs
- Database queries: Enable query logging if needed

## Security Considerations

- ✅ User authorization (users can only manage own reminders)
- ✅ Input validation on all forms
- ✅ CSRF protection on all forms
- ✅ SQL injection protection via Eloquent ORM
- ✅ XSS protection in email templates

## Performance Considerations

- Database index on `(next_trigger_at, notified, is_active)` for efficient querying
- Command runs every 5 minutes to balance responsiveness and server load
- Email sending is handled synchronously (consider queue for high volume)

## Future Enhancements

Potential improvements:
- Queue-based email sending for better performance
- Multiple recipients per reminder
- Different recurrence patterns (weekly, quarterly)
- Email delivery status tracking
- Reminder categories and templates
- Mobile push notifications
- Reminder history and analytics

## Support

For issues or questions:
1. Check Laravel logs in `storage/logs/`
2. Test email configuration in Settings
3. Verify database connectivity
4. Run manual command to test processing