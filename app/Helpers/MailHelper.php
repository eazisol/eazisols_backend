<?php

namespace App\Helpers;

use App\Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class MailHelper
{
    /**
     * Send an email using PHPMailer.
     *
     * @param  string  $to
     * @param  string  $toName
     * @param  string  $subject
     * @param  string  $view
     * @param  array  $data
     * @return bool
     * @throws \Exception
     */
    public static function send($to, $toName, $subject, $view, $data = [])
    {
        // Get mail settings from database
        $host = Setting::get('mail_host');
        $port = Setting::get('mail_port');
        $username = Setting::get('mail_username');
        $password = Setting::get('mail_password');
        $encryption = Setting::get('mail_encryption');
        $fromAddress = Setting::get('mail_from_address');
        $fromName = Setting::get('mail_from_name');
        
        // Log email attempt
        Log::info('Attempting to send email', [
            'to' => $to,
            'subject' => $subject,
            'host' => $host,
            'port' => $port,
            'from' => $fromAddress
        ]);
        
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        
        try {
            // Enable debug output to log
            $mail->SMTPDebug = 2; // 2 = debug server/client protocol
            $mail->Debugoutput = function($str, $level) {
                Log::debug("PHPMailer: $str");
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
            $mail->addAddress($to, $toName);
            
            // Set email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            
            // Get the email template content
            $content = view($view, $data)->render();
            $mail->Body = $content;
            $mail->AltBody = strip_tags($content);
            
            // Send the email
            $result = $mail->send();
            Log::info('Email sent successfully', ['to' => $to, 'subject' => $subject]);
            return $result;
        } catch (Exception $e) {
            Log::error('Failed to send email', [
                'error' => $mail->ErrorInfo,
                'exception' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ]);
            throw new \Exception('Failed to send email: ' . $mail->ErrorInfo);
        }
    }
} 