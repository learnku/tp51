<?php


namespace app\study\controller;


use think\facade\Url;
use think\Request;

class SqlController extends BashController
{
    public function index()
    {
        $this->data['form'] = [
            'action' => Url::build('sql/save'),
        ];
        $this->data['form2'] = [
            'action' => Url::build('sql/save2'),
        ];

        return $this->fetch();
    }

    public function save(Request $request)
    {
        $name = $request->param('name');
        $password = $request->param('password');

        echo '原本应该执行语句：';
        echo '<br>';
        echo 'SELECT * FROM `users` WHERE `name` = ${name} AND `password` = ${password}';
        echo '<br>';
        echo '<br>';
        echo '最终执行的语句：';
        echo '解释：sql 中 `--` 是注释符';
        echo '<br>';
        echo "SELECT * FROM `users` WHERE `name` = '${name}' AND `password` = '${password}'";
        echo '<br>';
        return '造成了 sql 被注入。';
    }

    public function save2(Request $request)
    {
        $name = addslashes($request->param('name'));
        $password = addslashes($request->param('password'));

        echo '原本应该执行语句：';
        echo '<br>';
        echo 'SELECT * FROM `users` WHERE `name` = ${name} AND `password` = ${password}';
        echo '<br>';
        echo '<br>';
        echo '最终执行的语句：';
        echo '<br>';
        echo "SELECT * FROM `users` WHERE `name` = '${name}' AND `password` = '${password}'";
        echo '<br>';
        return '有效防止了 sql 被注入。';
    }
}