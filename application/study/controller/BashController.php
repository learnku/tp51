<?php
/**
 * 基础控制器
 */

namespace app\study\controller;


use app\common\controller\BaseController as CommonBaseController;

class BashController extends CommonBaseController
{
    protected $middleware = ['ip', ['rate_limit', [60, 60]]];
}