<?php

namespace App\Http\Controllers;

class SetLocaleController extends Controller
{
    public function index($locale)
    {
        app()->setLocale($locale);
        session(['lang' => $locale]);

        $segments = str_replace(url('/'), '', url()->previous());
        $segments = array_filter(explode('/', $segments));
        array_shift($segments);
        array_unshift($segments, $locale);

        return redirect()->to(implode('/', $segments));
    }
}
