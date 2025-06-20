<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailSettings = Setting::getByGroup('email');
        
        return view('settings.index', compact('emailSettings'));
    }
    
    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $settings = $request->except('_token');
        
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
        
        // Clear config cache to apply new settings
        Artisan::call('config:clear');
        
        // Flush settings cache
        Setting::flushCache();
        
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
    
    /**
     * Send a test email to verify the email settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
        ]);
        
        try {
            \App\Helpers\MailHelper::send(
                $validated['test_email'],
                'Test User',
                'Test Email from Eazisols Dashboard',
                'emails.test',
                ['content' => 'This is a test email to verify your email settings.']
            );
            
            return redirect()->route('settings.index')->with('success', 'Test email sent successfully to ' . $validated['test_email']);
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
} 