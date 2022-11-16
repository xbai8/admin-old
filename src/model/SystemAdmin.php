<?php

namespace Hangpu8\Admin\model;

use Hangpu8\Admin\Model;

class SystemAdmin extends Model
{
    // 隐藏字段输出
    protected $hidden = ['password', 'delete_time'];
}
