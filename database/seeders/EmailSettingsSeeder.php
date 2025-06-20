<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'group' => 'email',
                'type' => 'select',
                'options' => json_encode(['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'mailgun' => 'Mailgun', 'ses' => 'Amazon SES', 'postmark' => 'Postmark', 'log' => 'Log', 'array' => 'Array']),
                'label' => 'Mail Driver',
                'description' => 'The mail driver to use for sending emails',
                'order' => 1,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'group' => 'email',
                'type' => 'text',
                'label' => 'Mail Host',
                'description' => 'The mail server host',
                'order' => 2,
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'group' => 'email',
                'type' => 'text',
                'label' => 'Mail Port',
                'description' => 'The mail server port',
                'order' => 3,
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'group' => 'email',
                'type' => 'text',
                'label' => 'Mail Username',
                'description' => 'The mail server username',
                'order' => 4,
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'group' => 'email',
                'type' => 'password',
                'label' => 'Mail Password',
                'description' => 'The mail server password',
                'order' => 5,
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'email',
                'type' => 'select',
                'options' => json_encode(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None']),
                'label' => 'Mail Encryption',
                'description' => 'The mail encryption protocol',
                'order' => 6,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@eazisols.com',
                'group' => 'email',
                'type' => 'text',
                'label' => 'Mail From Address',
                'description' => 'The email address that will be used to send emails',
                'order' => 7,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Eazisols',
                'group' => 'email',
                'type' => 'text',
                'label' => 'Mail From Name',
                'description' => 'The name that will be used to send emails',
                'order' => 8,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
} 