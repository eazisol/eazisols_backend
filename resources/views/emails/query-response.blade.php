<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Response to Your Inquiry - TEST VERSION</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #0066cc;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .message {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #0066cc;
            margin-bottom: 20px;
        }
        .test-marker {
            background-color: #ffcc00;
            padding: 5px;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="test-marker">
        TEST EMAIL - PLEASE CHECK MAILTRAP INBOX
    </div>
    
    <div class="header">
        <h1>{{ config('app.name', 'Eazisols') }}</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $query->name }},</p>
        
        <p>Thank you for contacting us regarding: <strong>{{ $query->subject ?? 'Your Inquiry' }}</strong></p>
        
        <div class="message">
            {!! nl2br(e($response)) !!}
        </div>
        
        <p>If you have any further questions, please don't hesitate to contact us.</p>
        
        <p>
            Best regards,<br>
            The Eazisols Team
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Eazisols') }}. All rights reserved.</p>
    </div>
</body>
</html> 