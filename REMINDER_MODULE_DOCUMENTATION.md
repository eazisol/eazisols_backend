# Monthly Email Reminder System - Complete Module Documentation

## 📋 Overview

The Monthly Email Reminder System is a comprehensive Laravel module that allows HR and administrative staff to create, manage, and automatically send recurring monthly email reminders. The system is fully integrated with the existing roles and permissions infrastructure and uses PHPMailer for reliable email delivery.

## 🏗️ System Architecture

### Core Components
- **Database Layer**: Migration, Model, and Seeders
- **Controller Layer**: RESTful controller with role-based permissions
- **View Layer**: Blade templates with SweetAlert2 integration
- **Email System**: PHPMailer integration with database settings
- **Background Processing**: Artisan command for automated email sending
- **Security Layer**: Role-based permissions and authentication

## 📁 File Structure

```
├── app/
│   ├── Console/Commands/
│   │   └── ProcessReminders.php              # Background processing command
│   ├── Http/Controllers/
│   │   └── ReminderController.php            # Main controller with CRUD operations
│   ├── Models/
│   │   └── Reminder.php                      # Eloquent model
│   └── Helpers/
│       └── MailHelper.php                    # PHPMailer integration
├── database/
│   ├── migrations/
│   │   └── 2025_07_15_101130_create_reminders_table.php
│   └── seeders/
│       ├── ReminderSeeder.php                # Sample data
│       ├── ReminderPermissionSeeder.php      # Permission creation
│       └── AssignReminderPermissionSeeder.php # Role assignments
├── resources/views/
│   ├── reminders/
│   │   ├── index.blade.php                   # List all reminders
│   │   ├── create.blade.php                  # Create new reminder
│   │   ├── edit.blade.php                    # Edit existing reminder
│   │   └── show.blade.php                    # View reminder details
│   └── emails/
│       └── reminder.blade.php                # Email template
└── routes/web.php                            # Route definitions
```

## 🗄️ Database Schema

### Reminders Table
```sql
CREATE TABLE reminders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    recipient_email VARCHAR(255) NOT NULL,
    recipient_name VARCHAR(255) NOT NULL,
    day_of_month TINYINT UNSIGNED NOT NULL,
    time TIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    next_trigger_at TIMESTAMP NULL,
    last_sent_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Key Fields Explanation
- `day_of_month`: Day of the month (1-31) when reminder should be sent
- `time`: Time of day for sending (HH:MM:SS format)
- `next_trigger_at`: Calculated next send date/time
- `last_sent_at`: Timestamp of last successful send
- `is_active`: Toggle for enabling/disabling reminders

## 🔐 Security & Permissions

### Permission System
- **Permission Key**: `dash_reminders`
- **Assigned Roles**: Admin, HR, Business Development
- **Authorization Method**: `auth()->user()->hasPermission('dash_reminders')`

### Route Protection
All reminder routes are protected by:
1. **Authentication Middleware**: `auth` middleware
2. **Permission Middleware**: Custom permission checking in controller

### Controller Security Implementation
```php
public function __construct()
{
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        if (!auth()->user()->hasPermission('dash_reminders')) {
            abort(403, 'Unauthorized access to reminders.');
        }
        return $next($request);
    });
}
```

## 📧 Email System Integration

### PHPMailer Configuration
- **Library**: PHPMailer/PHPMailer v6.10+
- **Settings Source**: Database via `Setting` model
- **Helper Class**: `App\Helpers\MailHelper`

### Email Settings (Database Stored)
```php
$settings = [
    'mail_host' => 'smtp.example.com',
    'mail_port' => '587',
    'mail_username' => 'user@example.com',
    'mail_password' => 'password',
    'mail_encryption' => 'tls',
    'mail_from_address' => 'noreply@example.com',
    'mail_from_name' => 'Company Name'
];
```

### Email Template
Location: `resources/views/emails/reminder.blade.php`
- Responsive HTML design
- Dynamic content injection
- Professional styling

## 🎨 User Interface

### SweetAlert2 Integration
All delete operations use SweetAlert2 modals following the project's standard pattern:

```javascript
Swal.fire({
    title: 'Delete Reminder',
    text: `Are you sure you want to delete "${title}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6'
});
```

### View Features
- **Index View**: Paginated list with search, filter, and bulk actions
- **Create/Edit Views**: Form validation and user-friendly interface
- **Show View**: Detailed information with action buttons
- **Responsive Design**: Mobile-friendly layout

## 🔄 Background Processing

### Artisan Command
```bash
php artisan reminders:process
```

### Command Features
- Processes all active reminders due for sending
- Updates `next_trigger_at` for next month
- Records `last_sent_at` timestamp
- Comprehensive logging and error handling
- Progress indicators and success/failure reporting

### Scheduling (Recommended)
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('reminders:process')
             ->dailyAt('09:00')
             ->withoutOverlapping();
}
```

## 🚀 Installation & Setup

### 1. Database Migration
```bash
php artisan migrate
```

### 2. Install PHPMailer
```bash
composer require phpmailer/phpmailer
```

### 3. Seed Permissions
```bash
php artisan db:seed --class=ReminderPermissionSeeder
php artisan db:seed --class=AssignReminderPermissionSeeder
```

### 4. Optional: Seed Sample Data
```bash
php artisan db:seed --class=ReminderSeeder
```

### 5. Configure Email Settings
Ensure email settings are configured in the database via the Settings panel.

## 📝 Usage Guide

### Creating a Reminder
1. Navigate to `/reminders`
2. Click "Create New Reminder"
3. Fill in required fields:
   - Title and description
   - Recipient email and name
   - Day of month (1-31)
   - Time (24-hour format)
4. Save the reminder

### Managing Reminders
- **View**: Click eye icon to see details
- **Edit**: Click edit icon to modify
- **Delete**: Click delete icon (SweetAlert2 confirmation)
- **Toggle Status**: Activate/deactivate reminders
- **Test Email**: Send immediate test email

### Monitoring
- Check `last_sent_at` timestamps
- Review `next_trigger_at` for upcoming sends
- Monitor application logs for processing results

## 🔧 API Endpoints

### RESTful Routes
```php
Route::resource('reminders', ReminderController::class);
Route::post('reminders/{reminder}/test', [ReminderController::class, 'sendTest'])->name('reminders.test');
Route::patch('reminders/{reminder}/toggle-status', [ReminderController::class, 'toggleStatus'])->name('reminders.toggle-status');
```

### Available Actions
- `GET /reminders` - List all reminders
- `GET /reminders/create` - Show create form
- `POST /reminders` - Store new reminder
- `GET /reminders/{id}` - Show specific reminder
- `GET /reminders/{id}/edit` - Show edit form
- `PUT/PATCH /reminders/{id}` - Update reminder
- `DELETE /reminders/{id}` - Delete reminder
- `POST /reminders/{id}/test` - Send test email
- `PATCH /reminders/{id}/toggle-status` - Toggle active status

## 🐛 Troubleshooting

### Common Issues

#### 1. PHPMailer Class Not Found
**Solution**: Run `composer require phpmailer/phpmailer`

#### 2. Email Not Sending
**Checklist**:
- Verify email settings in database
- Check SMTP credentials
- Review application logs
- Test with `php artisan reminders:process`

#### 3. Permission Denied
**Solution**: Ensure user has `dash_reminders` permission

#### 4. Next Trigger Date Not Updating
**Cause**: Command not running or failing
**Solution**: Check logs and run command manually

### Logging
All email operations are logged with detailed information:
- Successful sends
- Failed attempts with error messages
- SMTP debug information
- Processing statistics

## 📊 Performance Considerations

### Optimization Tips
1. **Batch Processing**: Command processes multiple reminders efficiently
2. **Database Indexing**: Consider indexes on `next_trigger_at` and `is_active`
3. **Email Queue**: For high volume, consider implementing queue system
4. **Caching**: Settings are cached for performance

### Monitoring
- Monitor email sending success rates
- Track processing time for large reminder sets
- Set up alerts for failed email deliveries

## 🔮 Future Enhancements

### Potential Features
1. **Multiple Recipients**: Support for multiple email addresses
2. **Email Templates**: Multiple template options
3. **Timezone Support**: Per-reminder timezone settings
4. **Attachment Support**: File attachments to reminders
5. **Advanced Scheduling**: Weekly, quarterly options
6. **Email Analytics**: Open rates, click tracking
7. **Notification Channels**: SMS, Slack integration

## 📚 Dependencies

### Required Packages
- `phpmailer/phpmailer`: ^6.10
- `laravel/framework`: ^8.0|^9.0|^10.0
- `sweetalert2`: ^11.0 (CDN)

### Laravel Features Used
- Eloquent ORM
- Blade Templates
- Artisan Commands
- Middleware
- Form Validation
- Database Migrations
- Seeders

## 🤝 Contributing

### Code Standards
- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add comprehensive comments
- Write unit tests for new features

### Testing
```bash
# Run feature tests
php artisan test --filter=ReminderTest

# Test email functionality
php artisan reminders:process --dry-run
```

## 📄 License & Support

This module is part of the EaziSols Dashboard system and follows the same licensing terms as the main application.

For support or questions, contact the development team or refer to the main application documentation.

---

**Last Updated**: July 15, 2025  
**Version**: 1.0.0  
**Author**: Eazisols Team