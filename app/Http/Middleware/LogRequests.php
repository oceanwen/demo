<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        $traceId = $request->header('X-Amzn-Trace-Id', 'N/A');
        $timeLocal = $_SERVER['REQUEST_TIME'] ?? null;
        $remoteAddr = $request->ip();
        $status = http_response_code();
        $requestTime = microtime(true) - LARAVEL_START;
        $upstreamResponseTime = $request->header('X-Upstream-Response-Time', 'N/A');

        Log::channel('request')->info('Request Log', [
            'time_local' => date('Y-m-d H:i:s', $timeLocal),
            'ip' => $request->ip(),
            'remote_addr' => $remoteAddr,
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'status' => $status,
            'trace_id' => $traceId,
            'request_time' => $requestTime,
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        return $next($request);
    }
}
