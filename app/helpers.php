<?php

use App\Models\Setting;
use App\Services\TranslationService;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null) {
        try {
            return Setting::getValue($key, $default);
        } catch (Exception $e) {
            return $default;
        }
    }
}



if (! function_exists('gtrans')) {
    function gtrans(string $text, ?string $locale = null): string
    {
        /** @var TranslationService $service */
        $service = app(TranslationService::class);

        return $service->translate($text, $locale);
    }
}
