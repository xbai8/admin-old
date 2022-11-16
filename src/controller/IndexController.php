<?php

namespace Hangpu8\Admin\controller;

use Hangpu8\Admin\Base;
use Hangpu8\Admin\utils\manager\VueRoutesMgr;

class IndexController extends Base
{
    public function index()
    {
    }

    /**
     * 获取管理员数据
     *
     * @return void
     */
    public function user()
    {
        $data = hp_admin([
            'username',
            'nickname',
            'headimg',
            'level'
        ]);
        return parent::successRes($data);
    }

    /**
     * 获取菜单数据
     *
     * @return void
     */
    public function menus()
    {
        $admin = hp_admin();

        $data = VueRoutesMgr::getRoutes((int) $admin['role_id']);
        return parent::successRes($data);
    }
}
