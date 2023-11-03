<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Exceptions;
use Illuminate\Support\Facades\Log;

class UriLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $redis_client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '172.27.0.6',
            'port'   => 6379,
        ]);
        // Redis key name
        $key = 'URI_LOG';

        // Number of requests to store in list
        $limit = 5000;

        $request_execution_time = microtime(true);
        $response = $next($request);
        $request_execution_time = microtime(true) - $request_execution_time;
        
        $requeset_log = array(  "url" => $request->path(),
                            "status" => $response->status(),
                            "execution_time" => $request_execution_time,
                            "time" => date("YmdHis"));
        $redis_client->rpush($key, serialize($requeset_log));

        return $response;
    }
}
