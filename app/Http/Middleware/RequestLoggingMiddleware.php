<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestLoggingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $startTime;

        if (app()->environment() !== 'testing') {
            Log::channel('api')->info('API Request', [
                'method' => $request->method(),
                'uri' => $request->getRequestUri(),
                'ip' => $request->ip(),
                'user_id' => auth()->id(),
                'status' => method_exists($response, 'status') ? $response->status() : 200,
                'duration_ms' => round($duration * 1000, 2),
            ]);
        }

        return $response;
    }
}
