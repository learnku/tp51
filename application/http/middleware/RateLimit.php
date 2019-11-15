<?php

namespace app\http\middleware;

use think\facade\Cache;
use think\Request;

/**
 * 访问速率限制中间件
 * @ 调用方式
 * @    1、$middleware = ['ip', 'rate_limit']                # 使用默认值
 * @    2、$middleware = ['ip', ['rate_limit', [30, 60]]]    # 60 秒内最多 30 次调用
 * @redis https://github.com/nicolasff/phpredis
 * @package app\http\middleware
 */
class RateLimit
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @param array $params 示例：[30, 60]  --- 60 秒内最多 30 次的调用【limit, reset】
     * @return mixed|\think\response\Json
     */
    public function handle(Request $request, \Closure $next, $params)
    {
        $this->getKey($request);
        halt(md5(uniqid(mt_rand(), true)));
        session('jiejie', 'liguanjie');
        halt(session('jiejie'));
        // halt($request);

        // 默认 60s 允许 60次访问
        $params = $params ?? [60, 60];
        $response = $next($request);

        // 以 ip 作为键
        $key = $request->ip();
        // redis 实例
        $redis = $this->redisClient();

        // X-RateLimit-Limit        同一个时间段所允许的请求的最大数目
        // X-RateLimit-Remaining    在当前时间段内剩余的请求的数量
        // X-RateLimit-Reset        为了得到最大请求数所需等待的毫秒数
        $limit  = $params[0];       // 限制次数
        $reset = $params[1];        // 限制时间周期

        // 响应给前端的 header
        $header = [
            'X-RateLimit-Limit' => $limit,
            'X-RateLimit-Remaining' => intval($limit) - 1,
        ];

        // 创建并存入 redis
        if (!$redis->exists($key)) {
            // 将当前时间段剩余的请求量存入 redis
            $redis->set($key, $limit, $reset);

            // 设置过期时间(s)
            // $redis->expire($key, $reset);
        }

        // 剩余请求数 <= 0
        // 超频访问
        if ($redis->get($key) <= 0) {
            return json([
                'status_code'=> 429,
                'message' => 'Too Many Requests',
            ], 429, array_merge($header, [
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset'=> $redis->ttl($key) . 's',  // 剩余过期时间(s)
            ]));
        }

        // 递减
        $header['X-RateLimit-Remaining'] = $redis->decr($key);

        // 输出到 header 头中
        foreach ($header as $key=> $item) {
            $response->header($key, $item);
        }

        return $response;
    }

    /**
     * 获取 key
     * @param Request $request
     * @return string
     */
    protected function getKey(Request $request)
    {
        $user_id = session('user_id');
        // 如果用户已登录
        if ($user_id) {
            return sha1($request->domain() . '|' .$user_id);
        }

        // 否则使用访客 ip
        return sha1($request->domain() . '|' . $request->ip());
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
