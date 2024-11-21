<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Lang;

class ValidateCsrfTokenMiddleware extends ValidateCsrfToken
{

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next): Response
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $exception) {
            throw  new TokenMismatchException(Lang::get('Your session has expired. Please refresh the page and try again.'));
        }
    }
}
