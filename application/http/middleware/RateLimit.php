<?php

namespace app\http\middleware;

/**
 * 访问速率限制中间件
 * @package app\http\middleware
 */
class RateLimit
{
    public function handle($request, \Closure $next)
    {
        halt('jiejie');

        return $next($request);
    }
}
