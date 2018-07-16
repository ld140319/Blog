<?php

namespace App\Http\Middleware;

use Closure;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //允许请求来自哪些域名
        $response->header('Access-Control-Allow-Origin', '*');
        //允许请求中包含哪些header
        $response->header('Access-Control-Allow-Headers', 'token,Origin, X-Requested-With, Content-Type, Accept');
        //允许请求采用哪些请求方式
        $response->header('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        //允许请求中携带cookie
        $response->header('Access-Control-Allow-Credentials', 'true');
        //自定义的请求头token
        $response->header('Access-Control-Expose-Headers', 'token');
        return $response;
    }
}
