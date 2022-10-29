<?php

namespace Hangpu8\Admin\validate;

use think\Validate;

class SystemAdmin extends Validate
{
    protected $rule =   [
        'role_id'           => 'require',
        'nickname'          => 'require',
        'username'          => 'require',
        'password'          => 'require',
    ];

    protected $message  =   [
        'role_id.require'   => '请选择所属角色',
        'nickname.require'  => '请输入用户昵称',
        'username.require'  => '请输入登录账号',
        'password.require'  => '请输入登录密码',
    ];

    protected $scene = [
        'login'             =>  ['username', 'password'],
        'form'              =>  ['role_id', 'nickname', 'username', 'password'],
    ];
}
