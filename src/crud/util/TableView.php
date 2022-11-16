<?php

namespace Hangpu8\Admin\crud\util;

trait TableView
{
    use Input, ClassParse;

    /**
     * 查询条件字段映射
     *
     * @return array
     */
    private function getTableWhereMap(): array
    {
        // 获取类属性
        $properties = $this->getProperties();
        $data = [];
        // 查询条件字段映射
        if (isset($properties['whereMap'])) {
            $data = $properties['whereMap'];
        }
        return $data;
    }

    /**
     * 筛选查询
     *
     * @return array
     */
    private function getScreen(): array
    {
        return [];
    }

    /**
     * 表格头部按钮
     *
     * @return array
     */
    private function getTableTopButton(): array
    {
        // 获取类属性
        $properties = $this->getProperties();
        $data = [];
        // 顶部按钮
        if (isset($properties['topButton'])) {
            $data = $properties['topButton'];
        }
        return $data;
    }

    /**
     * 表格右侧按钮
     *
     * @return array
     */
    private function getTableRightButton(): array
    {
        // 获取类属性
        $properties = $this->getProperties();
        $data = [];
        // 右侧按钮
        if (isset($properties['rightButton'])) {
            $button = $properties['rightButton'];
            $data['title'] = isset($button['title']) ? $button['title'] : '操作';
            $data['extra'] = isset($button['extra']) ? $button['extra'] : [];
            $data['button'] = isset($button['button']) ? $button['button'] : [];
        }
        return $data;
    }

    /**
     * 表格列
     *
     * @return array
     */
    private function getTableColumns(): array
    {
        // 获取类属性
        $properties = $this->getProperties();
        // 字段列
        $attrColumns = isset($properties['columns']) ? $properties['columns'] : [];
        $columns = $this->allColumns();
        $data = [];
        // 表格列
        foreach ($columns as $value) {
            $item = [];
            if (isset($attrColumns[$value['Field']])) {
                // 基础列
                $item['field'] = $value['Field'];
                $item['title'] = $value['Comment'];
                $item['value'] = $value['Default'];
                $item['extra'] = [];
                // 取控制器属性
                if (isset($attrColumns[$value['Field']])) {
                    $attr = $attrColumns["{$value['Field']}"];
                    // 进行字段注释替换
                    if (isset($attr['replace'])) {
                        $item['title'] = str_replace($attr['replace'], '', $value['Comment']);
                        unset($attr['replace']);
                    }
                    $item['extra'] = $attr;
                    $item['extra']['type'] = isset($attr['type']) ? $attr['type'] : 'text';
                }
                // 列元素
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * 是否分页
     *
     * @return boolean
     */
    private function getTablePaginate(): bool
    {
        // 获取类属性
        $properties = $this->getProperties();
        $paginate = false;
        // 是否分页
        if (isset($properties['paginate'])) {
            $paginate = $properties['paginate'];
        }
        return $paginate;
    }
}
