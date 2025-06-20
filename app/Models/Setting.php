<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'options',
        'label',
        'description',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            
            // If this is an email setting, clear config cache
            if ($setting->group === 'email') {
                try {
                    Artisan::call('config:clear');
                } catch (\Exception $e) {
                    \Log::error('Failed to clear config cache: ' . $e->getMessage());
                }
            }
        });
        
        static::deleted(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });
    }

    /**
     * Get a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if ($setting) {
                if ($setting->type === 'boolean') {
                    return $setting->value === '1' || $setting->value === 'true';
                }
                
                return $setting->value;
            }
            
            return $default;
        });
    }

    /**
     * Set a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function set($key, $value)
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            static::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
        
        Cache::forget('setting_' . $key);
    }

    /**
     * Get all settings by group.
     *
     * @param  string  $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)
            ->orderBy('order')
            ->get();
    }

    /**
     * Flush the settings cache.
     *
     * @return void
     */
    public static function flushCache()
    {
        $settings = static::all();
        
        foreach ($settings as $setting) {
            Cache::forget('setting_' . $setting->key);
        }
    }
} 