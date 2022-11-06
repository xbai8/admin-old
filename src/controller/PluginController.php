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
}
