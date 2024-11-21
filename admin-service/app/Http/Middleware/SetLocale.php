<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // زبان را از درخواست بگیرید (مثلاً از هدر یا Query Parameter)
        $locale = $request->header('Accept-Language', 'fa'); // پیش‌فرض: 'fa'

        // بررسی زبان معتبر
        if (in_array($locale, ['en', 'fa', 'ar'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

