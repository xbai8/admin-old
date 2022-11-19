<?php

namespace Hangpu8\Admin\utils\database;

use Illuminate\Database\Schema\Blueprint;

/**
 * @title 数据表操作
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait TableAction
{
    /**
     * 创建字段
     *
     * @param $column
     * @param Blueprint $table
     * @return mixed
     */
    protected function createColumn($column, Blueprint $table)
    {
        $method = $column['type'];
        $args = [$column['field']];
        if (stripos($method, 'int') !== false) {
            // auto_increment 会自动成为主键
            if ($column['auto_increment']) {
                $column['nullable'] = false;
                $column['default'] = '';
                $args[] = true;
            }
        } elseif (in_array($method, ['string', 'char']) || stripos($method, 'time') !== false) {
            if ($column['length']) {
                $args[] = $column['length'];
            }
        } elseif ($method === 'enum') {
            $args[] = array_map('trim', explode(',', $column['length']));
        } elseif (in_array($method, ['float', 'decimal', 'double'])) {
            if ($column['length']) {
                $args = array_merge($args, array_map('trim', explode(',', $column['length'])));
            }
        } else {
            $column['auto_increment'] = false;
        }

        $column_def = [$table, $method](...$args);
        if (!empty($column['comment'])) {
            $column_def = $column_def->comment($column['comment']);
        }

        if (!$column['auto_increment'] && $column['primary_key']) {
            $column_def = $column_def->primary(true);
        }

        if ($column['auto_increment'] && !$column['primary_key']) {
            $column_def = $column_def->primary(false);
        }
        $column_def = $column_def->nullable($column['nullable']);

        if ($column['primary_key']) {
            $column_def = $column_def->nullable(false);
        }

        if ($column['default'] && !in_array($method, ['text'])) {
            $column_def->default($column['default']);
        }
        return $column_def;
    }

    /**
     * 获取基础创建字段
     *
     * @return array
     */
    private function getBaseicColumns(): array
    {
        $data = [
            [
                "field" => "id",
                "auto_increment" => true,
                "comment" => "主键",
                "default" => null,
                "length" => "11",
                "nullable" => false,
                "primary_key" => true,
                "type" => "unsignedInteger"
            ],
            [
                "field" => "created_at",
                "auto_increment" => false,
                "comment" => "创建时间",
                "default" => null,
                "length" => null,
                "nullable" => false,
                "primary_key" => false,
                "type" => "dateTime"
            ],
            [
                "field" => "updated_at",
                "auto_increment" => false,
                "comment" => "更新时间",
                "default" => null,
                "length" => null,
                "nullable" => false,
                "primary_key" => false,
                "type" => "dateTime"
            ]
        ];
        return $data;
    }
}
