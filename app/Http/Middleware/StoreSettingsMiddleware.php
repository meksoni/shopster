<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class StoreSettingsMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $store = Store::first();
        view()->share('store', $store);


        return $next($request);
    }
}
