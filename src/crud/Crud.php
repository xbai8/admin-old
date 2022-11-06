<?php

namespace Hangpu8\Admin\crud;

use Exception;
use Hangpu8\Admin\builder\FormBuilder;
use Hangpu8\Admin\builder\ListBuilder;
use Hangpu8\Admin\crud\Data;
use Hangpu8\Admin\utils\Json;
use think\model;
use support\Request;

/**
 * @title 增删改查基类
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait Crud
{
    use Data, Json;

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
        // 获取前端输入
        [$where, $format, $page_size, $field, $order, $column, $allow_column] = $this->selectInput($request);

        // 获取查询模型
        $model = $this->model;
        // 查询条件
        foreach ($where as $field_name => $value) {
            if (is_array($value)) {
                if (in_array($value[0], ['>', '=', '<', '<>'])) {
                    $model = $model->where($field_name, $value[0], $value[1]);
                } elseif ($value[0] == 'in') {
                    $model = $model->whereIn($field_name, $value[1]);
                } else {
                    $model = $model->whereBetween($field_name, $value);
                }
            } else {
                $model = $model->where($field_name, $value);
            }
        }
        // 数据排序
        $model = $model->order($field, $order);
        // 设置分页
        $model = $model->paginate($page_size);
        // 列表渲染对应数据
        $builderColumn = Column::getTableColumn($allow_column);
        // 表格渲染
        $listBuilder = new ListBuilder;
        // 头部按钮
        foreach ($builderColumn['topButton'] as $value) {
            $pageData = isset($value['pageData']) ? (array)$value['pageData'] : [];
            $style = isset($value['style']) ? (array)$value['style'] : [];
            $listBuilder = $listBuilder->addTopButton(
                (string)$value['name'],
                (string)$value['title'],
                $pageData,
                $style,
            );
        }
        // 右侧按钮
        $rightButton = isset($builderColumn['rightButton']) ? $builderColumn['rightButton'] : [];
        if (isset($rightButton['button']) && count($rightButton['button']) > 0) {
            $rightExtra = array_merge(
                [
                    'type'      => 'template',
                    'template'  => 'rightButtonList',
                ],
                (array)$builderColumn['rightButton']['extra']
            );
            $listBuilder = $listBuilder->addColumn(
                'rightButtonList',
                (string)$builderColumn['rightButton']['title'],
                $rightExtra,
            );
            foreach ($builderColumn['rightButton']['button'] as $value) {
                $pageData = isset($value['pageData']) ? (array)$value['pageData'] : [];
                $style = isset($value['style']) ? (array)$value['style'] : [];
                $listBuilder = $listBuilder->addRightButton(
                    (string)$value['name'],
                    (string)$value['title'],
                    $pageData,
                    $style,
                );
            }
        }
        // 表格列
        foreach ($builderColumn['columns'] as $value) {
            // 添加列
            $listBuilder = $listBuilder
                ->addColumn(
                    (string)$value['field'],
                    (string)$value['title'],
                    (array)$value['extra'],
                );
        }
        // 获取列表数据
        $items = $model->toArray();
        // 设置渲染数据
        $listBuilder = $listBuilder->setData($items['data']);
        // 是否分页
        if ($builderColumn['paginate']) {
            $listBuilder = $listBuilder->setPage(
                (int)$items['total'],
                (int)$items['last_page'],
                (int)$items['per_page'],
                (int)$items['current_page']
            );
        }
        // 获取渲染规则
        $data = $listBuilder->create();
        // 返回结果集
        return Json::successRes($data);
    }

    /**
     * 添加数据
     *
     * @param Request $request
     * @return void
     */
    public function add(Request $request)
    {
        // 获取前端输入
        [$where, $format, $page_size, $field, $order, $column, $allow_column] = $this->selectInput($request);
        $method = 'POST';
        $builderColumn = Column::getFormColumn($allow_column, 'formAddColumns');
        if ($request->method() == $method) {
            // 获取数据
            $post = $request->post();
            // 数据验证
            if ($builderColumn['validate']) {
                hpValidate(
                    $builderColumn['validate']['class'],
                    $post,
                    $builderColumn['validate']['scene']['add']
                );
            }
            // 获取模型
            $model = $this->model;
            // 设置数据列
            foreach ($builderColumn['columns'] as $value) {
                if ($value['save'] && isset($post[$value['field']])) {
                    $fieldProps = $value['field'];
                    $model->$fieldProps = $post[$value['field']];
                }
            }
            // 更新保存
            if ($model->save()) {
                return Json::success('添加成功');
            } else {
                return Json::fail('添加失败');
            }
        } else {
            $builder = new FormBuilder;
            // 请求方式
            $builder = $builder->setMethod($method);
            // 表单列
            foreach ($builderColumn['columns'] as $value) {
                $builder = $builder->addRow(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    $value['value'],
                    $value['extra']
                );
            }
            // 获取表单规则
            $data = $builder->create();
            // 返回视图
            return Json::successRes($data);
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
        // 获取前端输入
        [$where, $format, $page_size, $field, $order, $column, $allow_column] = $this->selectInput($request);
        // 获取查询模型
        $model = $this->model;
        // 查询条件
        foreach ($where as $field_name => $value) {
            if (is_array($value)) {
                if (in_array($value[0], ['>', '=', '<', '<>'])) {
                    $model = $model->where($field_name, $value[0], $value[1]);
                } elseif ($value[0] == 'in') {
                    $model = $model->whereIn($field_name, $value[1]);
                } else {
                    $model = $model->whereBetween($field_name, $value);
                }
            } else {
                $model = $model->where($field_name, $value);
            }
        }
        $model = $model->find();
        if (!$model) {
            throw new Exception('该数据不存在');
        }
        $method = 'PUT';
        $builderColumn = Column::getFormColumn($allow_column, 'formEditColumns');
        if ($request->method() == $method) {
            // 获取数据
            $post = $request->post();
            // 数据验证
            if ($builderColumn['validate']) {
                hpValidate(
                    $builderColumn['validate']['class'],
                    $post,
                    $builderColumn['validate']['scene']['edit']
                );
            }
            // 设置数据列
            foreach ($builderColumn['columns'] as $value) {
                if ($value['save'] && isset($post[$value['field']])) {
                    $fieldProps = $value['field'];
                    $model->$fieldProps = $post[$value['field']];
                }
            }
            // 更新保存
            if ($model->save()) {
                return Json::success('修改成功');
            } else {
                return Json::fail('修改失败');
            }
        } else {
            $builder = new FormBuilder;
            // 请求方式
            $builder = $builder->setMethod($method);
            // 表单列
            foreach ($builderColumn['columns'] as $value) {
                $builder = $builder->addRow(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    $value['value'],
                    $value['extra']
                );
            }
            // 设置表单数据
            $data = $model->toArray();
            $builder = $builder->setFormData($data);
            // 获取表单规则
            $formBuilder = $builder->create();
            // 返回视图
            return Json::successRes($formBuilder);
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
        return Json::success('删除成功');
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
