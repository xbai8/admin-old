<?php

namespace Hangpu8\Admin\controller;

use Hangpu8\Admin\Base;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemAdmin;

class PluginController extends Base
{
    use Crud;
    protected $model = null;
    public function __construct()
    {
        $this->model = new SystemAdmin;
    }

    /**
     * 创建插件
     *
     * @return void
     */
    public function create()
    {
    }

    /**
     * 安装插件
     *
     * @return void
     */
    public function install()
    {
    }

    /**
     * 卸载插件
     *
     * @return void
     */
    public function uninstall()
    {
    }
}
