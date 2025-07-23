<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reminder->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .reminder-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .test-badge {
            background-color: #ffc107;
            color: #212529;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .next-reminder {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            {{ $isTest ? '[TEST] ' : '' }}{{ $reminder->title }}
            @if($isTest)
                <span class="test-badge">TEST EMAIL</span>
            @endif
        </h1>
    </div>

    <div class="content">
        <p>Hello {{ $user->name }},</p>

        @if($isTest)
            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <strong>⚠️ This is a test email</strong><br>
                This reminder email was sent as a test. The actual reminder will be sent according to your schedule.
            </div>
        @else
            <p>This is your scheduled reminder for <strong>{{ $reminder->title }}</strong>.</p>
        @endif

        <div class="reminder-details">
            <h3>📋 Reminder Details</h3>
            <p><strong>Title:</strong> {{ $reminder->title }}</p>
            <p><strong>Description:</strong></p>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 3px; margin-top: 10px;">
                {!! nl2br(e($reminder->description)) !!}
            </div>
            <p style="margin-top: 15px;">
                <strong>Scheduled:</strong> {{ $reminder->day_with_suffix }} of every month at {{ $reminder->formatted_time }}
            </p>
        </div>

        @if(!$isTest)
            <div class="next-reminder">
                <h4>📅 Next Reminder</h4>
                <p>Your next reminder for this task will be sent on 
                    <strong>{{ $reminder->next_trigger_at->timezone('Asia/Karachi')->format('F j, Y') }}</strong> 
                    at 
                    <strong>{{ $reminder->next_trigger_at->timezone('Asia/Karachi')->format('g:i A') }}</strong>.
                </p>
            </div>
        @endif

        <p>Please take the necessary action for this reminder.</p>

        @if($isTest)
            <p style="margin-top: 30px;">
                <em>If you received this test email successfully, your reminder system is working correctly!</em>
            </p>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated reminder from Eazisols.</p>
        <p>If you need to modify or disable this reminder, please log in to your dashboard.</p>
        <p><small>Sent on {{ now()->format('F j, Y \a\t g:i A') }}</small></p>
    </div>
</body>
</html>