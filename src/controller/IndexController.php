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
        $data = admin([
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
        $data = VueRoutesMgr::getRoutes();
        return parent::successRes($data);
    }
}
