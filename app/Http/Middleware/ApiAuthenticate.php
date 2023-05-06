<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;

class ApiAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (! session('user')) {
            return redirect('/login');
        }

        return $next($request);
    }
}

