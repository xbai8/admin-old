<?php

namespace Hangpu8\Admin;

use Webman\Bootstrap;

/**
 * @title HPAdmin服务启动
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Service implements Bootstrap
{
    /**
     * 启动服务
     *
     * @param [type] $worker
     * @return void
     */
    public static function start($worker)
    {
        // 是否是命令行环境?
        $is_console = !$worker;
        if ($is_console) {
            return;
        }
        // monitor进程不执行定时器
        if ($worker->name == 'monitor') {
            return;
        }
        // 引入函数库
        require_once __DIR__ . '/helpers.php';
    }
}
