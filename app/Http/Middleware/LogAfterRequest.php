<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogAfterRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure  $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if($request->path() != 'logs' && $request->path() != 'api/pusher/auth') {
            Log::info('app.requests.'.$request->method().' PATH: '.$request->path(), ['request' => $request->except(['file'])]);
            Log::info('app.response.'.$request->method().' PATH: '.$request->path(), ['response' => $response]);
        }
    }

}
