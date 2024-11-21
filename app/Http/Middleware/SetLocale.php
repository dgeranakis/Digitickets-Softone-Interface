<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Make sure current locale exists.
        $locale = $request->segment(1);

        if (!array_key_exists($locale, app()->config->get('app.locales'))) {
            $segments = $request->segments();
            $segments[0] = session('lang', app()->config->get('app.fallback_locale'));

            return redirect()->to(implode('/', $segments));
        }

        app()->setLocale($locale);
        session(['lang' => $locale]);

        return $next($request);
    }
}
