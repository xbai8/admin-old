<?php

namespace Hangpu8\Admin\crud;

use Exception;
use Hangpu8\Admin\builder\FormBuilder;
use Hangpu8\Admin\builder\ListBuilder;
use Hangpu8\Admin\crud\util\FormView;
use Hangpu8\Admin\crud\util\Input;
use Hangpu8\Admin\crud\util\TableView;
use Hangpu8\Admin\utils\Json;
use support\Model;
use support\Request;
use support\Response;

/**
 * @title CRUD事件处理
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait CrudEvent
{
    use Input, TableView, FormView, Json;

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * 表格之前（查询数据）
     *
     * @return Object
     */
    public function tableEventBefore(): Object
    {
        // 排序字段
        $field = $this->orderField();
        // 排序类型
        $order = $this->orderBy();
        // 查询条件
        $where = $this->getWhere();
        // 获取映射字段
        $tableWhereMap = $this->getTableWhereMap();
        // 获取查询模型
        $model = $this->model;
        // 查询条件
        $whereIndex = isset($tableWhereMap['index']) ? $tableWhereMap['index'] : [];
        foreach ($where as $field_name => $value) {
            // 映射字段
            $fieldMapName = isset($whereIndex[$field_name]) ? $whereIndex[$field_name] : $field_name;
            if (is_array($value)) {
                if (in_array($value[0], ['>', '=', '<', '<>'])) {
                    $model = $model->where($fieldMapName, $value[0], $value[1]);
                } elseif ($value[0] == 'in') {
                    $model = $model->whereIn($fieldMapName, $value[1]);
                } else {
                    $model = $model->whereBetween($fieldMapName, $value);
                }
            } else {
                $model = $model->where($fieldMapName, $value);
            }
        }
        // 数据排序
        $model = $model->orderBy($field, $order);

        // 返回模型
        return $model;
    }


    /**
     * 表格之后（渲染数据）
     *
     * @param [type] $model
     * @return Response
     */
    public function tableEventAfter($model): Response
    {
        // 分页数量
        $page_size = $this->getLimit();
        // 表格头部按钮
        $topButton = $this->getTableTopButton();
        // 表格右侧按钮
        $rightButton = $this->getTableRightButton();
        // 表格单元列
        $columns = $this->getTableColumns();
        // 表格渲染
        $builder = new ListBuilder;
        // 头部按钮
        foreach ($topButton as $value) {
            $pageData = isset($value['pageData']) ? (array)$value['pageData'] : [];
            $style = isset($value['style']) ? (array)$value['style'] : [];
            $builder = $builder->addTopButton(
                (string)$value['name'],
                (string)$value['title'],
                $pageData,
                $style,
            );
        }
        // 右侧按钮
        if (isset($rightButton['button']) && count($rightButton['button']) > 0) {
            $rightExtra = array_merge(
                [
                    'type'      => 'template',
                    'template'  => 'rightButtonList',
                ],
                (array)$rightButton['extra']
            );
            $builder = $builder->addColumn(
                'rightButtonList',
                (string)$rightButton['title'],
                $rightExtra,
            );
            foreach ($rightButton['button'] as $value) {
                $pageData = isset($value['pageData']) ? (array)$value['pageData'] : [];
                $style = isset($value['style']) ? (array)$value['style'] : [];
                $builder = $builder->addRightButton(
                    (string)$value['name'],
                    (string)$value['title'],
                    $pageData,
                    $style,
                );
            }
        }
        // 表格列
        foreach ($columns as $value) {
            // 添加列
            $builder = $builder
                ->addColumn(
                    (string)$value['field'],
                    (string)$value['title'],
                    (array)$value['extra'],
                );
        }
        // 是否分页
        $paginate = $this->getTablePaginate();
        $items = [];
        if ($paginate) {
            $paginate = $model->paginate($page_size)->toArray();
            $items = $paginate['data'];
            $builder = $builder->setPage(
                (int)$paginate['total'],
                (int)$paginate['last_page'],
                (int)$paginate['per_page'],
                (int)$paginate['current_page']
            );
        } else {
            $items = $model->get()->toArray();
        }
        // 解析控制器
        $class = $this->parse();
        // 获取控制器属性
        $properties = $this->getProperties();
        // 表格后置事件（处理已查询数据）
        if (isset($properties['crudEvent']['tableEventAfter']) && $properties['crudEvent']['tableEventAfter']) {
            $items = call_user_func(
                [
                    $class->name,
                    $properties['crudEvent']['tableEventAfter']
                ],
                $items
            );
        }
        // 设置渲染数据
        $builder = $builder->setData($items);
        // 获取渲染规则
        $data =  $builder->create();

        // 返回表单规则
        return Json::successRes($data);
    }


    /**
     * 添加表单渲染
     *
     * @return Response
     */
    public function addEventBefore(): Response
    {
        // 构造表单
        $builder = new FormBuilder;
        // 表单渲染列
        $columns = $this->getFormColumns('formAddColumns');
        // 请求方式
        $builder = $builder->setMethod('POST');
        // 表单列
        foreach ($columns as $value) {
            // 类回调
            if (
                isset($value['callback'][0]) && $value['callback'][0] &&
                isset($value['callback'][1]) && $value['callback'][1]
            ) {
                $value = call_user_func([
                    $value['callback'][0],
                    $value['callback'][1]
                ], $value);
            }
            // 处理上传组件
            if (isset($value['type']) && $value['type'] == 'upload') {
                $upload = config('plugin.hangpu8.admin.upload');
                $extra = _array_merge($upload, isset($value['extra']['props']) ? $value['extra']['props'] : []);
                $authorization = request()->header('Authorization');
                $extra['headers']['Authorization'] = $authorization;
                $value['extra']['props'] = $extra;
            }
            $builder = $builder->addRow(
                $value['field'],
                $value['type'],
                $value['title'],
                $value['value'],
                $value['extra']
            );
        }
        $data = $builder->create();
        // 返回视图构造器
        return Json::successRes($data);
    }


    /**
     * 添加表单保存
     *
     * @return void
     */
    public function addEventAfter(Request $request)
    {
        // 获取数据
        $post = $request->post();
        // 验证器
        $validate = $this->getFormValidate();
        // 数据验证
        if ($validate) {
            $scene = isset($validate['scene']['add'])
                ? $validate['scene']['add']
                : '';
            hpValidate(
                $validate['class'],
                $post,
                $scene
            );
        }
        // 获取模型
        $model = $this->model;
        // 设置数据列
        $callbackFun = 'formAddCallback';
        // 表单渲染列
        $columns = $this->getFormColumns('formAddColumns');
        // 表单回调
        $callback = $this->getCallback();
        if (isset($callback[$callbackFun])) {
            // 回调设置列
            $callbackCls = $callback[$callbackFun];
            $callbackData = call_user_func([
                $callbackCls,
                $callbackFun
            ], $post, $columns);
            foreach ($callbackData as $key => $value) {
                $model->$key = $value;
            }
        } else {
            // 内置设置列
            foreach ($columns as $value) {
                if ($value['save'] && isset($post[$value['field']])) {
                    $fieldProps = $value['field'];
                    if (isset($value['save']) && $value['save']) {
                        $model->$fieldProps = $post[$value['field']];
                    }
                }
            }
        }
        // 更新保存
        if (!$model->save()) {
            return Json::fail('添加失败');
        }
        return Json::success('添加成功');
    }


    /**
     * 更新表单渲染
     *
     * @param Request $request
     * @param [type] $model
     * @param [type] $method
     * @return Response
     */
    public function editEventBefore(Request $request, $model, $method): Response
    {
        // 获取解析类
        $class = $this->parse();
        // 构造表单
        $builder = new FormBuilder;
        // 表单渲染列
        $columns = $this->getFormColumns('formEditColumns');
        // 请求方式
        $builder = $builder->setMethod($method);
        // 表单列
        foreach ($columns as $value) {
            // 类回调
            if (
                isset($value['callback'][0]) && $value['callback'][0] &&
                isset($value['callback'][1]) && $value['callback'][1]
            ) {
                $value = call_user_func([
                    $value['callback'][0],
                    $value['callback'][1]
                ], $value);
            }
            // 处理上传组件
            if (isset($value['type']) && $value['type'] == 'upload') {
                $upload = config('plugin.hangpu8.admin.upload');
                $extra = _array_merge($upload, isset($value['extra']['props']) ? $value['extra']['props'] : []);
                $authorization = request()->header('Authorization');
                $extra['headers']['Authorization'] = $authorization;
                $value['extra']['props'] = $extra;
            }
            $builder = $builder->addRow(
                $value['field'],
                $value['type'],
                $value['title'],
                $value['value'],
                $value['extra']
            );
        }
        // 查询数据
        $data = $model->toArray();
        // 处理编辑表单专属视图数据（回调）
        $formEditDataCheckCallback = $this->formEditDataCheckCallback();
        if ($formEditDataCheckCallback) {
            $data = call_user_func(
                [
                    $class->name,
                    $formEditDataCheckCallback
                ],
                $data
            );
        }
        // 设置视图数据
        $builder = $builder->setFormData($data);
        // 获得表单规则
        $formRule = $builder->create();
        // 返回数据
        return Json::successRes($formRule);
    }


    /**
     * 更新表单保存
     *
     * @param Request $request
     * @param [type] $model
     * @param [type] $method
     * @return Response
     */
    public function editEventAfter(Request $request, $model, $method): Response
    {
        // 获取数据
        $post = $request->post();
        // 验证器
        $validate = $this->getFormValidate();
        // 数据验证
        if ($validate) {
            $scene = isset($validate['scene']['edit'])
                ? $validate['scene']['edit']
                : '';
            hpValidate(
                $validate['class'],
                $post,
                $scene
            );
        }
        // 设置数据列
        $callbackFun = 'formEditCallback';
        // 表单渲染列
        $columns = $this->getFormColumns('formEditColumns');
        // 表单回调
        $callback = $this->getCallback();
        // 检测内置还是外部控制器回调设置
        if (isset($callback[$callbackFun])) {
            // 回调设置列
            $callbackCls = $callback[$callbackFun];
            $callbackData = call_user_func([
                $callbackCls,
                $callbackFun
            ], $post, $columns);
            foreach ($callbackData as $key => $value) {
                $model->$key = $value;
            }
        } else {
            // 内置设置列
            foreach ($columns as $value) {
                if ($value['save'] && isset($post[$value['field']])) {
                    $fieldProps = $value['field'];
                    if (isset($value['save']) && $value['save']) {
                        $model->$fieldProps = $post[$value['field']];
                    }
                }
            }
        }
        // 更新保存
        if (!$model->save()) {
            return Json::fail('修改失败');
        }
        return Json::success('修改成功');
    }

    /**
     * 删除前置事件（查询数据）
     *
     * @param Request $request
     * @return Model
     */
    public function delEventBefore(Request $request): Model
    {
        $model = $this->getModelFind();
        return $model;
    }

    /**
     * 删除后置事件（查询数据后）
     *
     * @param Request $request
     * @param [type] $model
     * @return Response
     */
    public function delEventAfter(Request $request, $model): Response
    {
        if (!$model->delete()) {
            throw new Exception('删除失败');
        }
        return Json::success('删除成功');
    }
}
