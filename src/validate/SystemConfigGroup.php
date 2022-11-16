<?php

namespace Hangpu8\Admin\validate;

use think\Validate;

class SystemConfigGroup extends Validate
{
    protected $rule =   [
        'title'             => 'require',
        'name'              => 'require',
        'icon'              => 'require',
    ];

    protected $message  =   [
        'title.require'     => '请输入分组名称',
        'name.require'      => '请输入分组标识',
        'icon.require'      => '请输入分组图标',
    ];

    protected $scene = [
        'add'               =>  ['title', 'name', 'icon'],
        'edit'              =>  ['title', 'icon'],
    ];
}
