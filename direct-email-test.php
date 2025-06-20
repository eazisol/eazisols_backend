<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Bootstrap Laravel to get access to the database
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the email address from command line argument
$toEmail = $argv[1] ?? 'bilalsajid@gmail.com';

// Get mail settings from database
$host = Setting::get('mail_host', 'sandbox.smtp.mailtrap.io');
$port = Setting::get('mail_port', '2525');
$username = Setting::get('mail_username', 'c3b8710789d815');
$password = Setting::get('mail_password', '6bb233641300fc');
$encryption = Setting::get('mail_encryption', 'tls');
$fromAddress = Setting::get('mail_from_address', 'noreply@eazisols.com');
$fromName = Setting::get('mail_from_name', 'Eazisols');

echo "===== DIRECT EMAIL TEST =====\n";
echo "Sending to: $toEmail\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Username: $username\n";
echo "From: $fromName <$fromAddress>\n\n";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 3; // 3 = debug connection and data
    $mail->Debugoutput = function($str, $level) {
        echo "DEBUG: $str\n";
    };
    
    // Configure SMTP
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->Port = $port;
    
    // Set authentication
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;
    
    // Set encryption
    if ($encryption === 'tls') {
        $mail->SMTPSecure = 'tls';
    } elseif ($encryption === 'ssl') {
        $mail->SMTPSecure = 'ssl';
    } else {
        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
    }
    
    // Disable SSL verification (for testing only)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    // Set sender and recipient
    $mail->setFrom($fromAddress, $fromName);
    $mail->addAddress($toEmail, 'Test User');
    
    // Set email content
    $mail->isHTML(true);
    $mail->Subject = 'Direct Email Test';
    $mail->Body = '<h3>This is a direct test email</h3><p>If you received this email, the email system is working correctly.</p>';
    $mail->AltBody = 'This is a direct test email. If you received this email, the email system is working correctly.';
    
    // Send the email
    echo "Attempting to send email...\n";
    $mail->send();
    echo "Email sent successfully!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $mail->ErrorInfo . "\n";
    echo "Exception: " . $e->getMessage() . "\n";
} 