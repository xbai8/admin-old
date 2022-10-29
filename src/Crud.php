<?php

namespace Hangpu8\Admin;

use support\Model;
use support\Request;

/**
 * @title 增删改查基类
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait Crud
{
    /**
     * @var Model
     */
    protected $model = null;

    /**
     * 列表视图
     *
     * @return void
     */
    public function index()
    {
    }

    /**
     * 创建保存
     *
     * @return void
     */
    public function create()
    {
    }

    /**
     * 编辑视图
     *
     * @return void
     */
    public function edit()
    {
    }

    /**
     * 更新保存
     *
     * @return void
     */
    public function update()
    {
    }

    /**
     * 显示资源
     *
     * @return void
     */
    public function show()
    {
    }

    public function store()
    {
    }

    /**
     * 删除
     *
     * @return void
     */
    public function destroy()
    {
    }

    /**
     * 恢复
     *
     * @return void
     */
    public function recovery()
    {
    }
}
