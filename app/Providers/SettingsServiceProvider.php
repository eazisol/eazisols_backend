<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Only load settings if the settings table exists
        if (Schema::hasTable('settings')) {
            // Load mail settings from database
            $this->loadMailSettings();
        }
    }

    /**
     * Load mail settings from database.
     *
     * @return void
     */
    protected function loadMailSettings()
    {
        $mailSettings = Setting::where('group', 'email')->get();
    
        \Log::debug('Mail settings loaded:', $mailSettings->toArray());
        
        foreach ($mailSettings as $setting) {
            $configKey = str_replace('mail_', 'mail.', $setting->key);
            
            // Special case for mail.from
            if ($setting->key === 'mail_from_address' || $setting->key === 'mail_from_name') {
                $fromKey = str_replace('mail_from_', '', $setting->key);
                Config::set('mail.from.' . $fromKey, $setting->value);
            } else {
                Config::set($configKey, $setting->value);
            }
        }
        
        // Add SSL/TLS stream options to prevent verification issues
        Config::set('mail.mailers.smtp.stream', [
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);
        
        // Force Laravel to forget and recreate the mail transport
        if ($this->app->resolved('mail.manager')) {
            $this->app->forgetInstance('mail.manager');
            $this->app->forgetInstance('mailer');
        }
    }
} 