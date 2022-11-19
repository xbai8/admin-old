<?php

namespace Hangpu8\Admin\controller;

use Exception;
use Hangpu8\Admin\Base;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemConfigGroup;
use support\Request;

class SystemConfigGroupController extends Base
{
    // 一键CRUD
    use Crud;

    // 筛选查询

    // 事件定义
    public $crudEvent = [
        'delEventBefore'    => 'delBefore',
    ];

    // 表格头部按钮
    public $topButton = [
        [
            'name'      => 'add',
            'title'     => '添加',
            'pageData'  => [
                'path'  => 'hpadmin/SystemConfigGroup/add',
            ],
            'style'     => []
        ],
    ];
    // 表格列右侧按钮
    public $rightButton = [
        'title'         => '操作选项',
        'extra'         => [
            'template'  => 'rightButtonList',
        ],
        'button'        => [
            [
                'name'      => 'config',
                'title'     => '配置',
                'pageData'  => [
                    'path'  => 'hpadmin/SystemWebconfig/index',
                    'type'  => 'table',
                    'title' => '配置项列表',
                ],
                'style'     => [
                    'type'  => 'primary',
                ]
            ],
            [
                'name'      => 'edit',
                'title'     => '编辑',
                'pageData'  => [
                    'path'  => 'hpadmin/SystemConfigGroup/edit',
                ],
                'style'     => []
            ],
            [
                'name'          => 'del',
                'title'         => '删除',
                'pageData'      => [
                    'path'      => 'hpadmin/SystemConfigGroup/del',
                    'type'      => 'confirm',
                    'method'    => 'DELETE',
                    'title'     => '温馨提示',
                    'content'   => '是否确认删除该数据？',
                ],
                'style'         => [
                    'type'      => 'danger',
                ]
            ],
        ],
    ];

    // 表格列
    public $columns = [
        'id'                => [
            'width'         => 100
        ],
        'title'             => [],
        'name'              => [],
        'icon'              => [],
        'is_system'         => [
            'type'          => 'tag',
            'replace'       => '：0否，1是',
            'style'         => [
                [
                    'type'  => 'warning',
                ],
                [
                    'type'  => 'success',
                ],
            ],
            'options'       => ['否', '是'],
        ],
    ];

    // 是否有分页
    public $paginate = true;

    // 验证器
    public $validate = [
        'class'             => \Hangpu8\Admin\validate\SystemConfigGroup::class,
        'scene'             => [
            'add'           => 'add',
            'edit'          => 'edit',
        ],
    ];
    // 表单添加视图列
    public $formAddColumns = [
        'title'             => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [],
        ],
        'name'              => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [],
        ],
        'icon'              => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [],
        ],
    ];
    // 表单修改视图列
    public $formEditColumns = [
        'title'             => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [],
        ],
        'name'              => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => false,
            'extra'         => [
                'disabled'  => true,
            ],
        ],
        'icon'              => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [],
        ],
    ];

    /**
     * @var SystemConfigGroup
     */
    protected $model = null;

    // 构造函数
    public function __construct()
    {
        $this->model = new SystemConfigGroup;
    }

    /**
     * 删除前置事件
     *
     * @param Request $request
     * @return void
     */
    public function delBefore(Request $request)
    {
        $id = $request->get('id');
        $model = $this->model;
        $where = [
            ['id', '=', $id],
        ];
        $model = $model->where($where)->first();
        if ($model->is_system == '1') {
            throw new Exception('系统配置分组，禁止删除');
        }
        return $model;
    }
}
