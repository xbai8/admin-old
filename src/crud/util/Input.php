<?php

namespace Hangpu8\Admin\crud\util;

use Exception;
use Hangpu8\Admin\utils\Util;
use support\Model;

trait Input
{
    /**
     * @var Model
     */
    protected $model = null;

    /**
     * 获取每页数量
     *
     * @return integer
     */
    public function getLimit(): int
    {
        $page_size = (int)request()->get('limit', 20);
        return $page_size;
    }

    /**
     * 获取当前分页
     *
     * @return integer
     */
    public function getPage(): int
    {
        $page = (int)request()->get('page', 1);
        return $page;
    }

    /**
     * 获取模型全部字段
     *
     * @return array
     */
    public function allColumns(): array
    {
        $model = $this->model;
        $table = $model->getTable();
        $prefix = Util::getDatabase()['prefix'];
        $all_columns = Util::db()->select("SHOW FULL COLUMNS FROM `{$prefix}{$table}`");
        if (!$all_columns) {
            throw new Exception('表不存在', 2);
        }
        $data = array_map('get_object_vars', $all_columns);
        return $data;
    }

    /**
     * 获取字段映射
     *
     * @return array
     */
    public function allowColumn(): array
    {
        $all_columns = $this->allColumns();
        $allow_column = array_column($all_columns, 'Field', 'Field');
        return $allow_column;
    }

    /**
     * 获取查询条件
     *
     * @return array
     */
    public function getWhere(): array
    {
        $allow_column = $this->allowColumn();
        $where = request()->get();
        if (!is_array($where)) {
            return [];
        }
        foreach ($where as $column => $value) {
            if (
                $value === '' || !isset($allow_column[$column]) ||
                (is_array($value) && ($value[0] == 'undefined' || $value[1] == 'undefined'))
            ) {
                unset($where[$column]);
            }
        }
        return $where;
    }

    /**
     * 获取排序字段
     *
     * @return string
     */
    public function orderField(): string
    {
        $field = request()->get('field');
        $allow_column = $this->allowColumn();
        if (!in_array($field, $allow_column)) {
            $field = current($allow_column);
        }
        return $field;
    }

    /**
     * 获取排序类型
     *
     * @return string
     */
    public function orderBy(): string
    {
        $order = request()->get('order', 'desc');
        $order = $order === 'asc' ? 'asc' : 'desc';
        return $order;
    }

    /**
     * 获取模型查询数据
     *
     * @return Model
     */
    public function getModelFind(): Model
    {
        // 查询条件
        $where = $this->getWhere();
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
        // 获得查询模型
        $model = $model->first();
        if (!$model) {
            throw new Exception('该数据不存在');
        }
        // 返回模型
        return $model;
    }
}
