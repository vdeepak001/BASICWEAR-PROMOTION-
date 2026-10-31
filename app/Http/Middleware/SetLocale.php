<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (! $locale) {
            $googtrans = $request->cookie('googtrans');
            if ($googtrans && str_contains($googtrans, '/de')) {
                $locale = 'de';
            } else {
                $locale = config('app.locale', 'en');
            }
        }

        if (in_array($locale, ['en', 'de'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

