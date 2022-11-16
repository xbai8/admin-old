<?php

namespace Hangpu8\Admin\builder;

/**
 * @title 表格构造器
 * @desc 用于表格的构造生成器
 * @author 楚羽幽 <admin@hangpu.net>
 */
class ListBuilder
{
    // 数据
    private $data;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->data = [
            // 子数据
            'children'              => [
                // 是否开启懒加载
                'lazy'              => false,
                // 懒加载请求API
                'api'               => '',
                // 懒加载请求类型
                'method'            => '',
                // 懒加载行Key
                'field'             => 'id',
                // 是否展开全部行
                'defaultExpandAll'  => true,
                // 渲染嵌套数据的配置选项
                'treeProps'         => [
                    'hasChildren'   => 'hasChildren',
                    'children'      => 'children',
                ],
            ],
            'screen'                => [
                'api'               => '',
                'method'            => 'get',
                'submitBtn'         => [],
                'params'            => [],
                'rule'              => [],
            ],
            'topButtonList'         => [],
            'rightButtonList'       => [],
            'columns'               => [],
            'items'                 => [],
            'paginate'              => [
                'total'             => 0,
                'limit'             => 0,
                'page'              => 1
            ],
        ];
    }

    /**
     * 添加表格列
     *
     * @param string $field
     * @param string $title
     * @param array $extra
     * @return ListBuilder
     */
    public function addColumn(string $field, string $title, array $extra = []): ListBuilder
    {
        $column = [
            'field'                 => $field,
            'title'                 => $title,
            'extra'                 => []
        ];
        $column['extra']            = array_merge($column['extra'], $extra);
        $this->data['columns'][]    = $column;
        return $this;
    }

    /**
     * 设置列表数据
     *
     * @param array $data
     * @return ListBuilder
     */
    public function setData(array $data): ListBuilder
    {
        $this->data['items'] = $data;
        return $this;
    }

    /**
     * 设置数据分页
     *
     * @param integer $total
     * @param integer $last_page
     * @param integer $limit
     * @param integer $page
     * @return ListBuilder
     */
    public function setPage(int $total, int $last_page, int $limit = 10, int $page = 1): ListBuilder
    {
        $this->data['paginate'] = [
            'total'             => $total,
            'last_page'         => $last_page,
            'limit'             => $limit,
            'page'              => $page
        ];
        return $this;
    }

    /**
     * 添加顶部按钮
     *
     * @param string $name
     * @param string $title
     * @param array $pageData
     * @param array $style
     * @return ListBuilder
     */
    public function addTopButton(string $name, string $title, $pageData = [], $style = []): ListBuilder
    {
        $btn['field']           = $name;
        $btn['title']           = $title;
        $btn['pageData']        = [
            'path'              => '',
            'method'            => 'GET',
            'title'             => '温馨提示',
            'content'           => '',
            'type'              => 'modal', // 支持：page，modal，confirm，table
            'rowPrefix'         => [],
            'width'             => '60%',
            'height'            => '55vh',
            'params'            => [],
        ];
        $pageData               = array_merge($btn['pageData'], $pageData);
        $btn['pageData']        = $pageData;
        // 按钮样式
        $btn['style']           = array_merge($this->getButtonDefaultStyle(), $style);

        $this->data['topButtonList'][] = $btn;
        return $this;
    }

    /**
     * 添加右侧菜单
     *
     * @param string $field
     * @param string $title
     * @param array $pageData
     * @param array $style
     * @return ListBuilder
     */
    public function addRightButton(string $field, string $title, array $pageData = [], array $style = []): ListBuilder
    {
        $btn                    = [];
        $btn['field']           = $field;
        $btn['title']           = $title;
        $btn['pageData']        = [
            'path'                          => '',
            'method'                        => 'GET',
            'title'                         => '温馨提示',
            'content'                       => '',
            'type'                          => 'modal', // 支持：page，modal，confirm，table
            'rowPrefix'                     => [
                'id'
            ],
            'width'                         => '60%',
            'height'                        => '55vh',
            'params'                        => [],
        ];
        $pageData                           = array_merge($btn['pageData'], $pageData);
        $btn['pageData']                    = $pageData;

        // 按钮样式
        $btn['style']                       = array_merge($this->getButtonDefaultStyle(), $style);
        $this->data['rightButtonList'][]    = $btn;
        return $this;
    }

    /**
     * 获得默认按钮样式
     *
     * @param string $type
     * @param string $size
     * @return array
     */
    private function getButtonDefaultStyle(string $type = 'success', string $size = 'small'): array
    {
        $data                   = [
            // 类型 primary / success / warning / danger / info
            'type'              => $type,
            //尺寸 large / default /small
            'size'              => $size,
            //是否朴素按钮
            'plain'             => false,
            // 是否文字按钮
            'text'              => false,
            // 是否显示文字按钮背景颜色
            'bg'                => false,
            // 是否为链接按钮
            'link'              => false,
            //是否圆角按钮
            'round'             => false,
            //是否圆形按钮
            'circle'            => false,
            //是否加载中状态
            'loading'           => false,
            // 自定义加载中状态图标组件
            'loading-icon'      => '',
            //是否禁用状态
            'disabled'          => false,
            //图标类名
            'icon'              => '',
            //是否默认聚焦
            'autofocus'         => false,
            //原生 type 属性
            'nativeType'        => "button",
            // 是否显示按钮
            'show'              => true,
        ];
        return $data;
    }

    /**
     * 设置树子数据配置
     *
     * @param array $config
     * @return ListBuilder
     */
    public function setChildren(array $config): ListBuilder
    {
        $this->data['children'] = array_merge($this->data['children'], $config);
        return $this;
    }

    /**
     * 构造数据
     *
     * @return array
     */
    public function create(): array
    {
        return $this->data;
    }
}
