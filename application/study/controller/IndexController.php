<?php
namespace app\study\controller;

use think\facade\Url;

class IndexController extends BashController
{
    public function index()
    {
        $this->data['list'] = [
            [
                'name' => 'Xss 攻击演示',
                'href' => Url::build('study/xss/index'),
            ]
        ];
        return $this->fetch();
    }
}
