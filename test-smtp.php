<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\Setting;

// Bootstrap Laravel to get access to the database
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get mail settings
$host = Setting::get('mail_host');
$port = Setting::get('mail_port');
$username = Setting::get('mail_username');
$password = Setting::get('mail_password');
$encryption = Setting::get('mail_encryption');
$fromAddress = Setting::get('mail_from_address');
$fromName = Setting::get('mail_from_name');

echo "Testing SMTP connection with:\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Username: $username\n";
echo "Encryption: $encryption\n";
echo "From: $fromName <$fromAddress>\n\n";

// Create a SwiftMailer transport
$transport = new Swift_SmtpTransport($host, $port);
$transport->setUsername($username);
$transport->setPassword($password);
if (!empty($encryption)) {
    $transport->setEncryption($encryption);
}

// Set stream options
$transport->setStreamOptions([
    'ssl' => [
        'allow_self_signed' => true,
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

try {
    echo "Connecting to SMTP server...\n";
    // Try to start the connection
    $transport->start();
    echo "Connection successful!\n";
    
    // Create a mailer and message
    $mailer = new Swift_Mailer($transport);
    $message = new Swift_Message('Test Email');
    $message->setFrom([$fromAddress => $fromName]);
    $message->setTo(['test@example.com' => 'Test User']);
    $message->setBody('This is a test email.');
    
    // Send the message
    echo "Sending test email...\n";
    $result = $mailer->send($message);
    echo "Email sent successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // More detailed error information
    echo "\nDetailed error information:\n";
    echo "Error code: " . (method_exists($e, 'getCode') ? $e->getCode() : 'N/A') . "\n";
    echo "Error file: " . (method_exists($e, 'getFile') ? $e->getFile() : 'N/A') . "\n";
    echo "Error line: " . (method_exists($e, 'getLine') ? $e->getLine() : 'N/A') . "\n";
    
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "\nPrevious exception:\n";
        echo "Message: " . $e->getPrevious()->getMessage() . "\n";
        echo "Code: " . $e->getPrevious()->getCode() . "\n";
    }
} 