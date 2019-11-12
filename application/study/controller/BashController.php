<?php
/**
 * 基础控制器
 */

namespace app\study\controller;
use think\Controller;
use think\Response;
use think\facade\Env;
use think\facade\Request;

class BashController extends Controller
{
    // 前台页面所需要的数据
    protected $data = [
        'seo' => [
            'title' => 'title',
            'description' => 'description',
            'keywords' => 'description',
        ],
    ];

    /**
     * 重写 fetch 方法，用于打印输出到 视图的变量，便于调试（只在调试模式有效）
     * 加载模板输出
     * @access protected
     * @param  string $template 模板文件名
     * @param  array  $vars     模板输出变量
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        // 为视图自动绑定数据
        // $this->assign('data', $this->data);
        $this->assign($this->data);

        $print_r = Request::param('print_r');
        if (Env::get('app_debug') && 1 == $print_r) {
            // return json($this->view->data);
            return json($this->data);
        }
        return Response::create($template, 'view')->assign($vars)->config($config);
    }
}