<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserLanguage
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $preferredLanguage = $user ? $user->language : null;
        $preferredLanguage = $preferredLanguage ?? config('app.locale');
        app()->setLocale($preferredLanguage);
        return $next($request);
    }
}
