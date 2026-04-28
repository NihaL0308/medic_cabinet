<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale {
    public function handle(Request $request, Closure $next) {
        if ($request->hasSession()) {
            $locale = $request->session()->get('locale', 'fr');
        } else {
            $locale = 'fr';
        }

        if (!in_array($locale, ['fr', 'ar'])) {
            $locale = 'fr';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}