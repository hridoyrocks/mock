<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_title',
        'site_logo',
        'dark_mode_logo',
        'favicon',
        'contact_email',
        'contact_phone',
        'address',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'footer_text',
        'copyright_text',
        'meta_tags'
    ];

    protected $casts = [
        'meta_tags' => 'array'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when settings are updated
        static::saved(function () {
            Cache::forget('website_settings');
        });

        static::deleted(function () {
            Cache::forget('website_settings');
        });
        
        static::updated(function () {
            Cache::forget('website_settings');
        });
    }

    /**
     * Get the settings instance
     */
    public static function getSettings()
    {
        return Cache::remember('website_settings', 60, function () {
            return self::first() ?? self::create([
                'site_title' => 'IELTS Mock Platform',
                'copyright_text' => '© ' . date('Y') . ' IELTS Mock Platform. All rights reserved.'
            ]);
        });
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->site_logo ? asset('storage/' . $this->site_logo) : null;
    }

    /**
     * Get dark mode logo URL
     */
    public function getDarkModeLogoUrlAttribute()
    {
        return $this->dark_mode_logo ? asset('storage/' . $this->dark_mode_logo) : null;
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    /**
     * Check if social media links exist
     */
    public function hasSocialLinks()
    {
        return $this->facebook_url || $this->twitter_url || $this->instagram_url || 
               $this->youtube_url || $this->linkedin_url;
    }

    /**
     * Get site name for backward compatibility
     */
    public function getSiteNameAttribute()
    {
        return $this->site_title;
    }

    /**
     * Get social media links array
     */
    public function getSocialLinksAttribute()
    {
        $links = [];

        if ($this->facebook_url) {
            $links[] = ['name' => 'Facebook', 'url' => $this->facebook_url, 'icon' => 'fab fa-facebook-f'];
        }
        if ($this->twitter_url) {
            $links[] = ['name' => 'Twitter', 'url' => $this->twitter_url, 'icon' => 'fab fa-twitter'];
        }
        if ($this->instagram_url) {
            $links[] = ['name' => 'Instagram', 'url' => $this->instagram_url, 'icon' => 'fab fa-instagram'];
        }
        if ($this->youtube_url) {
            $links[] = ['name' => 'YouTube', 'url' => $this->youtube_url, 'icon' => 'fab fa-youtube'];
        }
        if ($this->linkedin_url) {
            $links[] = ['name' => 'LinkedIn', 'url' => $this->linkedin_url, 'icon' => 'fab fa-linkedin-in'];
        }

        return $links;
    }
}
