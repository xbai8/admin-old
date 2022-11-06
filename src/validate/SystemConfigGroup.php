<?php

namespace Hangpu8\Admin\validate;

use think\Validate;

class SystemConfigGroup extends Validate
{
    protected $rule =   [
        'title'             => 'require',
    ];

    protected $message  =   [
        'title.require'     => '请输入分组名称',
    ];

    protected $scene = [
        'add'               =>  ['title'],
        'edit'              =>  ['title'],
    ];
}
