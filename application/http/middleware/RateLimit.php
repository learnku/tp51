<?php

namespace app\http\middleware;

use think\console\Table;
use think\facade\Cache;
use think\facade\Response;
use think\Request;

/**
 * 访问速率限制中间件
 * @redis https://github.com/nicolasff/phpredis
 * @package app\http\middleware
 */
class RateLimit
{
    public function handle(Request $request, \Closure $next)
    {
        $response = $next($request);

        $key = $request->ip();   // 以 ip 作为键
        $redis = $this->redisClient();

        // 60s 允许 10次访问
        $limit = 10;            // X-RateLimit-Limit        同一个时间段所允许的请求的最大数目
        $remaining = 10;        // X-RateLimit-Remaining    在当前时间段内剩余的请求的数量
        $reset = $expire = 60;  // X-RateLimit-Reset        为了得到最大请求数所需等待的秒数

        // 响应给前端查看
        $header = [
            'X-RateLimit-Limit' => 10,
            'X-RateLimit-Remaining' => 10,
        ];

        if (!$redis->exists($key)) {
            // 将当前时间段剩余的请求量存入 redis
            $redis->set($key, $limit, $reset);

            // 设置过期时间
            // $redis->expire($key, $reset);
        } else {
            // 获取过期时间
            $expire = $redis->get($key);

            // 递减
            $header['X-RateLimit-Remaining'] = $redis->decr($key);
        }

        if ($redis->exists($key) && $expire <= 0) {
            return json([
                'status_code'=> 429,
                'message' => 'Too Many Requests',
            ], 429, array_merge($header, [
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset'=> $redis->ttl($key) . 's',  // 剩余过期时间
            ]));
        }

        // 输出到 header 头中
        foreach ($header as $key=> $item) {
            $response->header($key, $item);
        }

        return $response;
    }


    /**
     * 返回 redis 实例对象（只为 编辑器对 redis 实例的友好提示）
     * 利用 php7 的返回值类型来做。
     * @return \Redis
     */
    private function redisClient(): \Redis
    {
        return Cache::store('redis')->handler();
    }
}
