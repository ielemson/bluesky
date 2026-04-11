<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromSession
{
    protected array $allowed = ['en', 'zh', 'fr', 'es'];

    public function handle(Request $request, Closure $next)
    {
        $locale = session('app_locale', 'en');

        if (! in_array($locale, $this->allowed, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
