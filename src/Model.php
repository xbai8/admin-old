<?php

namespace Hangpu8\Admin;

use think\Model as ThinkModel;

/**
 * @title 数据库基类
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Model extends ThinkModel
{
    // 设置数据库连接方式
    protected $connection = 'plugin.hangpu8.admin.mysql';

    // 自动时间戳
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
}
