<!DOCTYPE html>
<html>
<head>
    <title>Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            background-color: #4a6cf7;
            color: white;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Eazisols Test Email</h2>
        </div>
        
        <p>Hello,</p>
        
        <p>{{ $content }}</p>
        
        <p>If you received this email, your email configuration is working correctly.</p>
        
        <p>Thank you,<br>The Eazisols Team</p>
        
        <div class="footer">
            <p>This is an automated message from the Eazisols Dashboard.</p>
        </div>
    </div>
</body>
</html> 