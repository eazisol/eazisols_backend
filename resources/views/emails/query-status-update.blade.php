<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Update for Your Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #28a745;
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
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }
        .status-new {
            background-color: #ffc107;
        }
        .status-in-progress {
            background-color: #007bff;
        }
        .status-resolved {
            background-color: #28a745;
        }
        .status-closed {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Eazisols') }}</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $query->name }},</p>
        
        <p>We are writing to inform you that the status of your inquiry has been updated:</p>
        
        <p>
            <strong>Subject:</strong> {{ $query->subject ?? 'Your Inquiry' }}<br>
            <strong>New Status:</strong> 
            <span class="status status-{{ str_replace('_', '-', $query->status) }}">
                {{ ucfirst(str_replace('_', ' ', $query->status)) }}
            </span>
        </p>
        
        @if($query->status === 'resolved')
            <p>Your inquiry has been resolved. If you have any further questions, please don't hesitate to contact us.</p>
        @elseif($query->status === 'in_progress')
            <p>We are currently working on your inquiry and will get back to you as soon as possible.</p>
        @elseif($query->status === 'closed')
            <p>Your inquiry has been closed. If you need to discuss this matter further, please submit a new inquiry.</p>
        @endif
        
        <p>
            Best regards,<br>
            The {{ config('app.name', 'Eazisols') }} Team
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Eazisols') }}. All rights reserved.</p>
    </div>
</body>
</html> 