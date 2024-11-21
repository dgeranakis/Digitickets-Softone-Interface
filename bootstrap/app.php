<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        using: function () {

            $locale = \Request::segment(1);
            app()->setLocale($locale);

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->prefix($locale)
                ->group(base_path('routes/web.php'));

            Route::prefix($locale . '/' . config('constants.admin_prefix'))
                ->middleware('web')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', SetLocale::class);

        $middleware->web(replace: [
            'Illuminate\Foundation\Http\Middleware\ValidateCsrfToken' => 'App\Http\Middleware\ValidateCsrfTokenMiddleware'
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if (!$request->expectsJson()) {
                if ($request->routeIs('admin.*')) {
                    return route('admin.login');
                }
                return route('login');
            }
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->routeIs('admin.*') && auth()->user()->is_admin) {
                return route('admin.dashboard');
            }
            return route('member-account');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
