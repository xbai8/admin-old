<?php

namespace Hangpu8\Admin\builder;

use FormBuilder\Driver\CustomComponent;
use FormBuilder\Factory\Elm;
use FormBuilder\Form;

/**
 * @title 表单构造器
 * @desc 用于表单UI快速生成
 * @author 楚羽幽 <admin@hangpu.net>
 */
class FormBuilder extends Form
{
    // 表单对象
    private $builder;
    // 选项卡对象
    private $tabBuilder;
    // 表单数据规则
    private $data;
    // 请求对象
    private $request;

    /**
     * 构造函数
     *
     * @param string $url
     * @param array $rule
     * @param array $config
     */
    public function __construct(string $url = '', array $rule = [], array $config = [])
    {
        $this->request = request();
        if (!$url) {
            $url = substr($this->request->uri(), 1);
        }
        $this->url = $url;
        $this->builder = Form::elm($url, $rule, $config);
    }

    /**
     * 表单配置
     *
     * @param string $name
     * @param array $data
     * @return $this
     */
    public function setConf(string $name, array $value)
    {
        $config = $this->builder->formConfig();
        if (count($value) <= 1) {
            $config[$name] = array_merge($config[$name], $value);
        } else {
            $config[$name] = $value;
        }
        $this->builder->setConfig($config);
        return $this;
    }

    /**
     * 添加表单行
     *
     * @param string $field 组件字段
     * @param string $type 组件类型
     * @param string $title 组件标题
     * @param [type] $value 组件默认值
     * @param array $extend 扩展数据
     * @return $this
     */
    public function addRow(string $field, string $type, string $title, $value = '', array $extra = [])
    {
        if ($type == 'custom') {
            // 创建自定义组件
            $component = new CustomComponent($extra['type']);
            // 设置字段，默认数据等
            $component
                ->field($field)
                ->title($title)
                ->value($value);
            // 设置组件属性
            $extraList = isset($extra['extra']) && is_array($extra['extra']) ? $extra['extra'] : [];
        } else {
            // 普通表单类型
            $component = Elm::$type($field, $title, $value);
            $extraList = is_array($extra) ? $extra : [];
        }
        if ($extraList) {
            foreach ($extraList as $componentType => $componentTypeValue) {
                $component->$componentType($componentTypeValue);
            }
        }
        $this->builder->append($component);
        return $this;
    }

    /**
     * 添加选项卡
     *
     * @param string $active 默认展示标识
     * @return $this
     */
    public function initTabs(string $active)
    {
        // 创建自定义组件
        $component = new CustomComponent('el-tabs');
        $component->props([
            'value'                     => $active
        ]);
        $this->tabBuilder               = $component;
        // 返回资源对象
        return $this;
    }

    /**
     * 创建表单分割线
     *
     * @param string $title
     * @return $this
     */
    public function addDivider(string $title)
    {
        // 创建自定义组件
        $component = new CustomComponent('el-divider');
        // 设置属性
        $component
            ->appendChild($title)
            ->appendRule('wrap', ['show' => false])
            ->appendRule('native', false)
            ->appendRule('_fc_drag_tag', 'el-divider')
            ->appendRule('_fc_drag_tag', 'el-divider')
            ->appendRule('hidden', false)
            ->appendRule('display', true);
        // 设置组件属性
        if (isset($extra) && $extra) {
            $component->props($extra);
        }
        $this->builder->append($component);
        return $this;
    }

    /**
     * 添加子面板数据
     *
     * @param string $title 选项卡名称
     * @param string $value 选项家标识
     * @param array $children 选项卡内容数据
     * @return $this
     */
    public function addTab(string $title, string $value, array $children)
    {
        $component[]                        = [
            'type'                          => 'el-tab-pane',
            'props'                         => [
                'label'                     => $title,
                'name'                      => $value,
            ],
            'children'                      => $children
        ];
        $this->tabBuilder->appendChildren($component);
        // 返回资源对象
        return $this;
    }

    /**
     * 设置行数据
     *
     * @param array $data 表单数据
     * @return $this
     */
    public function setFormData(array $data)
    {
        $this->builder->formData($data);
        return $this;
    }

    /**
     * 设置请求方式
     *
     * @param [type] $method
     * @return $this
     */
    public function setMethod($method = 'GET')
    {
        $this->builder->setMethod(strtoupper($method));
        return $this;
    }

    /**
     * 设置请求地址
     *
     * @param [type] $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->builder->setAction($action);
        return $this;
    }

    /**
     * 结束选项卡表单
     *
     * @return $this
     */
    public function endTabs()
    {
        $this->builder->append($this->tabBuilder);
        return $this;
    }

    /**
     * 快速生成表单
     *
     * @return array
     */
    public function create()
    {
        $apiUrl = $this->builder->getAction();
        $method = $this->builder->getMethod();
        $this->data['http']['api'] = $apiUrl;
        $this->data['http']['method'] = $method;
        $this->data['config'] = $this->builder->formConfig();
        $this->data['formRule'] = $this->builder->formRule();
        return $this->data;
    }

    /**
     * 获取builder生成类对象
     *
     * @return $this
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}
