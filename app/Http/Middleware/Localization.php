<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Xinax\LaravelGettext\Facades\LaravelGettext;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = LaravelLocalization::getCurrentLocaleRegional();
        LaravelGettext::setLocale($locale);
        return $next($request);
    }
}
