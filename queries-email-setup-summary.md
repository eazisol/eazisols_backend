# Queries Module Email Functionality

## Overview

We've implemented email functionality for the Queries module with the following features:

1. Email notifications when responding to a query
2. Email notifications when a query status changes
3. Customizable email templates
4. Preview functionality for email templates (in development environment)

## Files Created/Modified

### Email Templates
- `resources/views/emails/query-response.blade.php` - Template for responses to queries
- `resources/views/emails/query-status-update.blade.php` - Template for status update notifications

### Mailable Classes
- `app/Mail/QueryResponseMail.php` - Handles formatting and sending query responses
- `app/Mail/QueryStatusUpdateMail.php` - Handles formatting and sending status updates

### Controllers
- `app/Http/Controllers/Query/QueryController.php` - Updated to send emails
- `app/Http/Controllers/Query/EmailPreviewController.php` - Added for previewing email templates

### Routes
- Added email preview routes in `routes/web.php`

## How to Use

### Responding to Queries
1. Navigate to a query's detail page
2. Enter your response in the "Email Message" field
3. Click "Send Email Response"
4. The system will send the email and mark the query as resolved

### Status Updates
When you change a query's status via the "Manage Query" panel, the system automatically:
1. Updates the query status in the database
2. Sends a status update email to the customer

### Previewing Email Templates
In development environment, you can preview how emails will look using these URLs:
- `/email/preview/query-response` - Preview the response email
- `/email/preview/query-status/resolved` - Preview the status update email (resolved status)
- `/email/preview/query-status/in_progress` - Preview the status update email (in progress status)
- `/email/preview/query-status/closed` - Preview the status update email (closed status)

## Adding New Email Templates

To create additional email templates:

1. Create a new Blade view in `resources/views/emails/`
2. Create a new Mailable class in `app/Mail/`
3. Update the relevant controller to use your new Mailable

## Configuration

Before using the email functionality, make sure to configure your mail settings in the `.env` file. See the `email-setup-guide.md` file for detailed instructions. 