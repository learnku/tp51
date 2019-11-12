<?php
/**
 * 生成模块
 * 运行命令：php think build
 */
return [
    // 定义 study 模块的自动生成
    'study'=> [
        '__dir__'    => ['controller', 'view'],
        'controller' => ['XssController'],
        'view' => ['xss/index'],
    ],
];