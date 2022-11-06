<?php

namespace Hangpu8\Admin\crud;

class Column
{
    /**
     * 表结构转表格渲染
     *
     * @param array $data
     * @return array
     */
    public static function getTableColumn(array $columns): array
    {
        $control = request()->controller;
        // 获取控制器属性
        $class = new \ReflectionClass($control);
        $properties = $class->getDefaultProperties();

        // 设置列
        $data = [
            'screen'        => [],
            'topButton'     => [],
            'rightButton'   => [],
            'columns'       => [],
            'paginate'      => false,
        ];
        // 字段列
        $attrColumns = isset($properties['columns']) ? $properties['columns'] : [];
        // 列存在则继续
        if ($attrColumns) {
            // 顶部按钮
            if (isset($properties['topButton'])) {
                $data['topButton'] = $properties['topButton'];
            }
            // 右侧按钮
            if (isset($properties['rightButton'])) {
                $rightButton = $properties['rightButton'];
                $data['rightButton']['title'] = isset($rightButton['title']) ? $rightButton['title'] : '操作';
                $data['rightButton']['extra'] = isset($rightButton['extra']) ? $rightButton['extra'] : [];
                $data['rightButton']['button'] = isset($rightButton['button']) ? $rightButton['button'] : [];
            }
            // 是否分页
            if (isset($properties['paginate'])) {
                $data['paginate'] = $properties['paginate'];
            }
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
                    $data['columns'][] = $item;
                }
            }
        }
        return $data;
    }

    /**
     * 获取表单列
     *
     * @param array $columns
     * @param string $type
     * @return array
     */
    public static function getFormColumn(array $columns, string $type): array
    {
        $control = request()->controller;
        // 获取控制器属性
        $class = new \ReflectionClass($control);
        $properties = $class->getDefaultProperties();
        // 设置列
        $data = [
            'validate'      => [],
            'columns'       => [],
        ];
        // 验证器
        $validate = isset($properties['validate']) ? $properties['validate'] : [];
        if (isset($validate['class'])) {
            $data['validate']['class'] = $validate['class'];
            $scene = isset($validate['scene']) ? $validate['scene'] : [];
            $data['validate']['scene']['add'] = isset($scene['add']) ? $scene['add'] : '';
            $data['validate']['scene']['edit'] = isset($scene['edit']) ? $scene['edit'] : '';
        }
        // 字段列
        $attrs = isset($properties[$type]) ? $properties[$type] : [];
        // 表单列
        foreach ($columns as $value) {
            $item = [];
            if (isset($attrs[$value['Field']])) {
                // 基础列
                $attrField = $attrs[$value['Field']];
                $item['field'] = $value['Field'];
                $item['type'] = isset($attrField['type']) ? $attrField['type'] : 'input';
                $item['save'] = isset($attrField['save']) ? $attrField['save'] : false;
                $item['title'] = $value['Comment'];
                $item['value'] = isset($attrField['value']) ? $attrField['value'] : '';
                $item['extra'] = isset($attrField['extra']) ? $attrField['extra'] : [];
                // 替换字符串
                if (isset($attrField['replace']) && $attrField['replace']) {
                    $item['title'] = str_replace($attrField['replace'], '', $value['Comment']);
                }
                // 列元素
                $data['columns'][] = $item;
            }
        }
        // 返回数据
        return $data;
    }
}
