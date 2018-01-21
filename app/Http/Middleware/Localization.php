<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $languages = config('lang');

        $locale = $request->hasHeader('Accept-Language') &&
            in_array(
                $request->header('Accept-Language'),
                $languages
            ) ? $request->header('Accept-Language') : 'en';
        
        app()->setLocale($locale);

        return $next($request);
    }
}
