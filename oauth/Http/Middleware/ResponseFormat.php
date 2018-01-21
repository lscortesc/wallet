<?php

namespace Oauth\Http\Middleware;

use Closure;
use Oauth\Factory\FormatterFactory;

/**
 * Class ResponseFormat
 * @package App\Http\Middleware
 */
class ResponseFormat
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
        $request->merge([
            'formatter' => FormatterFactory::build($request)
        ]);

        return $next($request);
    }
}
