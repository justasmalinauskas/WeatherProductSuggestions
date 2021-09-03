<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheSaveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $key = 'request|'.$request->url();
        if(!$this->checkIfCached($key)) {
            $response = $next($request);
            Cache::put($key, $response, 300);
            return $response;
        } else {
            return Cache::get($key);
        }

    }


    private function checkIfCached($key): bool
    {
        return Cache::has($key);
    }
}
