<?php

namespace Hangpu8\Admin\validate;

use think\Validate;

class SystemAdmin extends Validate
{
    protected $rule =   [
        'username'          => 'require',
        'password'          => 'require',
        'nickname'          => 'require',
        'role_id'           => 'require',
    ];

    protected $message  =   [
        'username.require'  => '请输入登录账号',
        'password.require'  => '请输入登录密码',
        'nickname.require'  => '请输入用户昵称',
        'role_id.require'   => '请选择所属角色',
    ];

    protected $scene = [
        'login'             =>  ['username', 'password'],
        'add'               =>  ['role_id', 'nickname', 'username', 'password'],
        'edit'              =>  ['role_id', 'nickname', 'username', 'password'],
    ];
}
