<?php

namespace Hangpu8\Admin\crud;

use Exception;
use Hangpu8\Admin\crud\Data;
use Hangpu8\Admin\crud\util\ClassParse;
use Hangpu8\Admin\crud\util\Input;
use Hangpu8\Admin\crud\util\TableView;
use Hangpu8\Admin\utils\Json;
use support\Request;

/**
 * @title 增删改查基类
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait Crud
{
    use Data, Json, Input, ClassParse, CrudEvent, TableView;

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * 列表视图
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        // 解析控制器
        $class = $this->parse();
        // 获取控制器属性
        $properties = $this->getProperties();

        // 表格前置事件
        if (isset($properties['crudEvent']['tableEventBefore']) && $properties['crudEvent']['tableEventBefore']) {
            $model = call_user_func(
                [
                    $class->name,
                    $properties['crudEvent']['tableEventBefore']
                ],
                $request
            );
        } else {
            // 内置查询
            $model = $this->tableEventBefore();
        }
        if (!$model || !is_object($model)) {
            throw new Exception('构造表格数据错误');
        }
        // 内置渲染
        return $this->tableEventAfter($model);
    }

    /**
     * 添加数据
     *
     * @param Request $request
     * @return void
     */
    public function add(Request $request)
    {
        // 解析控制器
        $class = $this->parse();
        // 获取控制器属性
        $properties = $this->getProperties();
        $method = 'POST';
        if ($request->method() == $method) {
            // 表单前置事件（处理数据）
            if (isset($properties['crudEvent']['addEventAfter']) && $properties['crudEvent']['addEventAfter']) {
                return call_user_func(
                    [
                        $class->name,
                        $properties['crudEvent']['addEventAfter']
                    ],
                    $request
                );
            } else {
                // 内置查询
                return $this->addEventAfter($request);
            }
        } else {
            // 表单前置事件（渲染表单）
            if (isset($properties['crudEvent']['addEventBefore']) && $properties['crudEvent']['addEventBefore']) {
                return call_user_func(
                    [
                        $class->name,
                        $properties['crudEvent']['addEventBefore']
                    ],
                    $request
                );
            } else {
                // 内置渲染
                $builder = $this->addEventBefore();
                $data = $builder->create();
                return Json::successRes($data);
            }
        }
    }

    /**
     * 修改数据
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        // 解析控制器
        $class = $this->parse();
        // 获取控制器属性
        $properties = $this->getProperties();
        // 获得查询模型
        $model = $this->getModelFind();
        // 请求类型
        $method = 'PUT';
        // 执行线路
        if ($request->method() == $method) {
            // 表单前置事件（处理数据）
            if (isset($properties['crudEvent']['editEventAfter']) && $properties['crudEvent']['editEventAfter']) {
                return call_user_func(
                    [
                        $class->name,
                        $properties['crudEvent']['editEventAfter']
                    ],
                    $request,
                    $model,
                    $method
                );
            } else {
                // 内置查询
                return $this->editEventAfter($request, $model, $method);
            }
        } else {
            // 表单前置事件（渲染表单）
            if (isset($properties['crudEvent']['editEventBefore']) && $properties['crudEvent']['editEventBefore']) {
                return call_user_func(
                    [
                        $class->name,
                        $properties['crudEvent']['editEventBefore']
                    ],
                    $request,
                    $model,
                    $method
                );
            } else {
                // 内置渲染
                return $this->editEventBefore($request, $model, $method);
            }
        }
    }

    /**
     * 删除数据
     *
     * @param Request $request
     * @return void
     */
    public function del(Request $request)
    {
        // 解析控制器
        $class = $this->parse();
        // 获取控制器属性
        $properties = $this->getProperties();

        // 删除前置事件（获得查询模型）
        if (isset($properties['crudEvent']['delEventBefore']) && $properties['crudEvent']['delEventBefore']) {
            $model = call_user_func(
                [
                    $class->name,
                    $properties['crudEvent']['delEventBefore']
                ],
                $request
            );
        } else {
            // 内置查询
            $model = $this->delEventBefore($request);
        }
        // 删除后置事件（执行删除）
        if (isset($properties['crudEvent']['delEventAfter']) && $properties['crudEvent']['delEventAfter']) {
            return call_user_func(
                [
                    $class->name,
                    $properties['crudEvent']['delEventAfter']
                ],
                $request,
                $model
            );
        } else {
            // 内置查询
            return $this->delEventAfter($request, $model);
        }
    }

    /**
     * 显示资源
     *
     * @param Request $request
     * @return void
     */
    public function detail(Request $request)
    {
    }

    /**
     * 软删除恢复
     *
     * @param Request $request
     * @return void
     */
    public function recovery(Request $request)
    {
    }
}
