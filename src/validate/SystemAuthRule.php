<?php

namespace Hangpu8\Admin\validate;

use think\Validate;

class SystemAuthRule extends Validate
{
    protected $rule =   [
        'title'             => 'require',
        'path'              => 'require',
        'pid'               => 'require',
        'module'            => 'require',
        'namespace'         => 'require',
        'auth_params'       => 'require',
        'sort'              => 'require',
        'method'            => 'require',
    ];

    protected $message  =   [
        'title.require'         => '请输入菜单名称',
        'path.require'          => '请输入请求地址',
        'pid.require'           => '请选择父级菜单',
        'module.require'        => '请输入模块名称',
        'namespace.require'     => '请输入类所在命名空间',
        'sort.require'          => '请输入菜单排序',
        'method.require'        => '请选择请求类型',
    ];

    protected $scene = [
        'add'               =>  [
            'title',
            'path',
            'pid',
            'module',
            'namespace',
            'sort',
            'method',
        ],
        'edit'              =>  [
            'title',
            'path',
            'pid',
            'module',
            'namespace',
            'sort',
            'method',
        ],
    ];
}
