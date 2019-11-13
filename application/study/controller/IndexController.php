<?php
namespace app\study\controller;

use think\facade\Url;

class IndexController extends BashController
{
    public function index()
    {
        $this->data['list'] = [
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
        ];
        return $this->fetch();
    }

    public function phpinfo()
    {
        phpinfo();
    }
}
