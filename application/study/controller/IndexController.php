<?php
namespace app\study\controller;

use think\facade\Url;

class IndexController extends BashController
{
    public function index()
    {
        $this->data['list'] = [
            [
                'name' => 'PHP HASH 密码',
                'href' => Url::build('password/index'),
            ],
            [
                'name' => 'PHP 公私钥加解密',
                'href' => Url::build('openssl/index'),
            ],
            [
                'name' => 'XSS 跨站脚本攻击',
                'href' => Url::build('xss/index'),
            ],
            [
                'name' => 'SQL 注入攻击',
                'href' => Url::build('sql/index'),
            ],
            [
                'name' => 'XSRF/CSRF 跨站请求伪造  ',
                'href' => Url::build('csrf/index'),
            ],
        ];
        return $this->fetch();
    }

    public function phpinfo()
    {
        phpinfo();
    }
}
