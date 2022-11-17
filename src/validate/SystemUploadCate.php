<?php

namespace Hangpu8\Admin\validate;

use think\Validate;

class SystemUploadCate extends Validate
{
    protected $rule =   [
        'title'             => 'require',
        'dir_name'          => 'require',
        'sort'              => 'require',
    ];

    protected $message  =   [
        'title.require'     => '请输入分类名称',
        'dir_name.require'  => '请输入目录名称',
        'sort.require'      => '请输入分类排序',
    ];

    protected $scene = [
        'add'               =>  ['title', 'dir_name', 'sort'],
        'edit'              =>  ['title', 'sort'],
    ];
}
