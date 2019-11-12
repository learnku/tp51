<?php
namespace app\study\controller;

use think\facade\Url;
use think\Request;

class XssController extends BashController
{
    public function index()
    {
        $this->data['form'] = [
            'action'=> Url::build('study/xss/save')
        ];
        $this->data['form2'] = [
            'action'=> Url::build('study/xss/save2')
        ];
        return $this->fetch();
    }

    /**
     * 演示 XSS
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $xss = $request->param('xss_input');

        return $xss;
    }

    /**
     * 演示过滤 XSS
     * @param Request $request
     * @return mixed|string
     */
    public function save2(Request $request)
    {
        $xss = $request->param('xss_input');
        $xss = trim($xss);              // 清理空格
        // $xss = strip_tags($xss);        // 过滤 html 标签
        $xss = htmlspecialchars($xss);  // 将字符内容转化为html实体（过滤 XSS）
        $xss = addslashes($xss);        // 防止 SQL 注入（增加反引号）

        return $xss;
    }
}