<?php

namespace Hangpu8\Admin\crud\util;

trait FormView
{
    use Input, ClassParse;

    /**
     * 验证器
     *
     * @return array
     */
    public function getFormValidate(): array
    {
        // 解析类属性
        $properties = $this->getProperties();
        $data = [];
        // 验证器
        $validate = isset($properties['validate']) ? $properties['validate'] : [];
        if (isset($validate['class'])) {
            $data['class'] = $validate['class'];
            $scene = isset($validate['scene']) ? $validate['scene'] : [];
            $data['scene']['add'] = isset($scene['add']) ? $scene['add'] : '';
            $data['scene']['edit'] = isset($scene['edit']) ? $scene['edit'] : '';
        }
        return $data;
    }
    /**
     * 表单元素列
     *
     * @return array
     */
    public function getFormColumns(string $type): array
    {
        // 解析类属性
        $properties = $this->getProperties();
        // 字段列
        $columns = isset($properties[$type]) ? $properties[$type] : [];
        // 全部字段
        $allColumns = $this->allColumns();
        $data = [];
        // 表单列
        foreach ($columns as $field => $form) {
            $item = [];
            foreach ($allColumns as $value) {
                if ($field == $value['Field']) {
                    // 基础列
                    $item['field'] = $value['Field'];
                    $item['type'] = isset($form['type']) ? $form['type'] : 'input';
                    $item['callback'] = isset($form['callback']) ? $form['callback'] : [];
                    $item['save'] = isset($form['save']) ? $form['save'] : false;
                    $item['title'] = $value['Comment'];
                    $item['value'] = isset($form['value']) ? $form['value'] : '';
                    $item['extra'] = isset($form['extra']) ? $form['extra'] : [];
                    // 替换字符串
                    if (isset($form['replace']) && $form['replace']) {
                        $item['title'] = str_replace($form['replace'], '', $value['Comment']);
                    }
                    // 列元素
                    $data[] = $item;
                }
            }
        }
        return $data;
    }

    /**
     * 数据处理回调
     *
     * @return array
     */
    public function getCallback(): array
    {
        // 数据回调方法
        $methods = $this->getClassMethods();
        $data = [];
        foreach ($methods as $fun) {
            // 添加修改操作前缀
            if (in_array($fun->name, ['formAddCallback', 'formEditCallback'])) {
                $data[$fun->name] = $fun->class;
            }
        }
        return $data;
    }

    /**
     * 编辑专属表单数据处理回调
     *
     * @return string
     */
    public function formEditDataCheckCallback(): string
    {
        // 解析类属性
        $properties = $this->getProperties();
        $data = '';
        // 是否处理表单渲染视图数据（函数名）
        if (isset($properties['formEditViewCheck'])) {
            $data = $properties['formEditViewCheck'];
        }
        return $data;
    }
}
