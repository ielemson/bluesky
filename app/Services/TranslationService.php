<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $defaultLocale = 'en';

    public function translate(string $text, ?string $locale = null): string
    {
        $text = trim($text);
        if ($text === '') {
            return $text;
        }

        $locale = $locale ?: app()->getLocale() ?: $this->defaultLocale;

        // Avoid translating default language
        if ($locale === $this->defaultLocale) {
            return $text;
        }

        $cacheKey = 'gtrans:' . $locale . ':' . md5($text);

        return Cache::rememberForever($cacheKey, function () use ($text, $locale) {
            return $this->safeCallApi($text, $locale);
        });
    }

    protected function safeCallApi(string $text, string $locale): string
    {
        try {
            $tr = new GoogleTranslate();
            $tr->setSource($this->defaultLocale);
            $tr->setTarget($locale);

            return $tr->translate($text) ?? $text;
        } catch (\Throwable $e) {
            Log::warning('Translation failed', [
                'locale' => $locale,
                'hash'   => md5($text),
                'error'  => $e->getMessage(),
            ]);

            return $text; // fallback: original text
        }
    }
}
