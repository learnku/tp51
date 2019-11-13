<?php

namespace app\http\middleware;

class Ip
{
    public function handle($request, \Closure $next)
    {
        // 黑名单(应该存放到 redis 中才对)
        $blackList = [
            // '192.168.10.10',
        ];

        // 客户端 ip
        $clientIp = $request->ip();

        // 黑名单用户拒绝访问
        if (in_array($clientIp, $blackList)) {
            return json([
                'msg' => '您的 ip:' .$clientIp . '存在于本网站黑名单列表中，解决访问！',
            ]);
        }

        // 继续执行
        return $next($request);
    }
}
