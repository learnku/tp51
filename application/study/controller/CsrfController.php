<?php
/**
 * Created by PhpStorm.
 * User: GucciLee
 * Date: 2019/11/13
 * Time: 15:33
 */

namespace app\study\controller;


use think\facade\Session;
use think\facade\Url;
use think\Request;

class CsrfController extends BashController
{
    public function index()
    {
        // 生成 token
        $hash = hash_hmac("sha256", time(), mt_rand(), true);
        $token = strtr(base64_encode($hash), [
            '=' => '',
            '+' => '_',
            '-' => '_',
            '/' => '_',
            '\\' => '_',
        ]);

        // 存储 session
        Session::set('csrf', $token);
        $this->data['form'] = [
            'action'=> Url::build('store'),
            'csrf'=> $token
        ];

        return $this->fetch();
    }

    public function store(Request $request)
    {
        $confirm = $request->param('confirm');
        $csrf = $request->param('csrf');
        $csrfSession = Session::get('csrf');

        // 验证 csrf token
        if ($csrf !== $csrfSession) {
            return '无效请求';
        }

        if ($confirm === 'yes') {
            return '可以继续执行逻辑';
        }
    }
}