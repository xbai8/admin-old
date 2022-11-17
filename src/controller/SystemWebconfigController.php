<?php

namespace Hangpu8\Admin\controller;

use Hangpu8\Admin\Base;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemWebconfig;

class SystemWebconfigController extends Base
{
    use Crud;
    // 查询条件映射
    public $whereMap = [
        'index'      => ['id' => 'cid'],
    ];

    // 表格头部按钮
    public $topButton = [
        [
            'name'      => 'add',
            'title'     => '添加',
            'pageData'  => [
                'path'  => 'hpadmin/SystemWebconfig/add',
                'query' => ['id'],
            ],
            'style'     => []
        ],
    ];

    // 表格列右侧按钮
    public $rightButton = [
        'title'         => '操作选项',
        'extra'         => [
            'template'  => 'rightButtonList',
            'width'     => 150
        ],
        'button'        => [
            [
                'name'      => 'edit',
                'title'     => '编辑',
                'pageData'  => [
                    'path'  => 'hpadmin/SystemWebconfig/edit',
                ],
                'style'     => []
            ],
            [
                'name'          => 'del',
                'title'         => '删除',
                'pageData'      => [
                    'path'      => 'hpadmin/SystemWebconfig/del',
                    'type'      => 'confirm',
                    'method'    => 'DELETE',
                    'title'     => '温馨提示',
                    'content'   => '是否确认删除该数据？',
                ],
                'style'         => []
            ],
        ],
    ];

    // 表格列
    public $columns = [
        'id'                => [
            'width'         => 80,
        ],
        'title'             => [],
        'name'              => [],
        'type'              => [],
    ];
    // 是否分页
    public $paginate = true;

    // 模型
    protected $model = null;

    // 构造函数
    public function __construct()
    {
        $this->model = new SystemWebconfig;
    }

    public function form()
    {
    }
}
