<?php

namespace Hangpu8\Admin\crud;

use Exception;
use Hangpu8\Admin\utils\Json;
use Hangpu8\Admin\utils\Util;
use support\Request;

trait Data
{
    use Json;
    /**
     * @param Request $request
     * @return array|\support\Response
     */
    protected function selectInput(Request $request)
    {
        $field = $request->get('field');
        $order = $request->get('order', 'descend');
        $format = $request->get('format', 'normal');
        $page_size = $request->get('limit', $format === 'tree' ? 1000 : 20);
        $order = $order === 'ascend' ? 'asc' : 'desc';
        $where = $request->get();
        $table = $this->model->getTable();

        $all_columns = Util::db()->query("SHOW FULL COLUMNS FROM `{$table}`");
        if (!$all_columns) {
            throw new Exception('表不存在', 2);
        }
        $allow_column = array_column($all_columns, 'Field', 'Field');
        if (!in_array($field, $allow_column)) {
            $field = current($allow_column);
        }
        foreach ($where as $column => $value) {
            if (
                $value === '' || !isset($allow_column[$column]) ||
                (is_array($value) && ($value[0] == 'undefined' || $value[1] == 'undefined'))
            ) {
                unset($where[$column]);
            }
        }
        $field_column = array_values($allow_column);

        return [$where, $format, $page_size, $field, $order, $field_column, $all_columns, $table];
    }

    /**
     * @param $items
     * @return \support\Response
     */
    protected function formatSelect($items)
    {
        $formatted_items = [];
        foreach ($items as $item) {
            $formatted_items[] = [
                'label' => $item->title ?? $item->name ?? $item->id,
                'value' => $item->id
            ];
        }
        return json::successRes($formatted_items);
    }

    /**
     * 树
     *
     * @param $items
     * @return \support\Response
     */
    protected function formatTree($items)
    {
        $items_map = [];
        foreach ($items as $item) {
            $items_map[$item->id] = [
                'title' => $item->title ?? $item->name ?? $item->id,
                'value' => (string)$item->id,
                'key' => (string)$item->id,
                'pid' => $item->pid,
            ];
        }
        $formatted_items = [];
        foreach ($items_map as $index => $item) {
            if ($item['pid'] && isset($items_map[$item['pid']])) {
                $items_map[$item['pid']]['children'][] = &$items_map[$index];
            }
        }
        foreach ($items_map as $item) {
            if (!$item['pid']) {
                $formatted_items[] = $item;
            }
        }
        return json::successRes($formatted_items);
    }

    /**
     * 表格树
     *
     * @param $items
     * @return \support\Response
     */
    protected function formatTableTree($items)
    {
        $items_map = [];
        foreach ($items as $item) {
            $items_map[$item->id] = $item->toArray();
        }
        $formatted_items = [];
        foreach ($items_map as $index => $item) {
            if ($item['pid'] && isset($items_map[$item['pid']])) {
                $items_map[$item['pid']]['children'][] = &$items_map[$index];
            }
        }
        foreach ($items_map as $item) {
            if (!$item['pid']) {
                $formatted_items[] = $item;
            }
        }
        return $this->json(0, 'ok', $formatted_items);
    }
}
