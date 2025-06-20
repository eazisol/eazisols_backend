# Email Configuration Guide for Queries Module

To enable email functionality for the queries module, you need to configure your mail settings in the `.env` file. Follow these steps:

## 1. Configure Mail Settings in .env

Add or update the following settings in your `.env` file:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@eazisols.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Common SMTP Providers

#### For Gmail:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail-address@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail-address@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```
Note: For Gmail, you need to use an "App Password" if you have 2FA enabled.

#### For Mailtrap (Development/Testing):
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

#### For SendGrid:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 2. Test Email Configuration

You can test your email configuration by running the following Artisan command:

```
php artisan tinker
```

Then in the tinker console:

```php
Mail::raw('Test email from Laravel', function($message) { $message->to('your-email@example.com')->subject('Test Email'); });
```

## 3. Creating Additional Email Templates

To create additional email templates:

1. Create a new Blade view in `resources/views/emails/` directory
2. Create a new Mailable class in `app/Mail/` directory
3. Use the new Mailable in your controller

### Example:

1. Create a new template at `resources/views/emails/query-status-update.blade.php`
2. Create a new Mailable at `app/Mail/QueryStatusUpdateMail.php`
3. Use it in your controller:

```php
Mail::to($query->email)->send(new QueryStatusUpdateMail($query));
```

## 4. Email Queue (Optional)

For better performance, you can queue emails instead of sending them synchronously:

1. Configure a queue driver in your `.env` file:
```
QUEUE_CONNECTION=database
```

2. Create the queue tables:
```
php artisan queue:table
php artisan migrate
```

3. Make your Mailable implement ShouldQueue:
```php
class QueryResponseMail extends Mailable implements ShouldQueue
{
    // ...
}
```

4. Run a queue worker:
```
php artisan queue:work
```

## Troubleshooting

- Check your mail server logs
- Use Mailtrap for testing emails in development
- Make sure your mail server allows connections from your application
- Check firewall settings if emails are not being sent 