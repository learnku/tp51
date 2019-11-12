<?php
namespace app\study\controller;

use think\facade\Url;

class IndexController extends BashController
{
    public function index()
    {
        $this->data['list'] = [
            [
                'name' => 'XSS 跨站脚本攻击',
                'href' => Url::build('study/xss/index'),
            ],
            [
                'name' => 'SQL 注入攻击',
                'href' => Url::build(''),
            ],
        ];
        return $this->fetch();
    }
}
