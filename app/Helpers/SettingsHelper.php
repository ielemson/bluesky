<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    public static function get($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    public static function siteName()
    {
        return self::get('site_name', 'Your E-Commerce Store');
    }

    public static function siteEmail()
    {
        return self::get('site_email', 'admin@example.com');
    }

    public static function siteLogo()
    {
        return self::get('site_logo');
    }

    public static function contactEmail()
    {
        return self::get('contact_email', 'contact@example.com');
    }

    public static function contactPhone()
    {
        return self::get('contact_phone');
    }

    public static function contactAddress()
    {
        return self::get('contact_address');
    }

    public static function metaTitle()
    {
        return self::get('meta_title', 'Your E-Commerce Store');
    }

    public static function metaDescription()
    {
        return self::get('meta_description', 'Best online shopping experience');
    }

    
}