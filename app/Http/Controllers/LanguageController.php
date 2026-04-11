<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected array $allowed = ['en', 'zh', 'fr', 'es'];

    public function switch(Request $request)
    {
        $request->validate([
            'locale' => 'required|string',
        ]);

        $locale = $request->input('locale');

        if (! in_array($locale, $this->allowed, true)) {
            $locale = 'en';
        }

        session(['app_locale' => $locale]);

        return back();
    }
}
