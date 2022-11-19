<?php

namespace Hangpu8\Admin\controller;

use Exception;
use Hangpu8\Admin\Base;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemUploadCate;
use support\Request;

class SystemUploadCateController extends Base
{
    // 一键CRUD
    use Crud;

    /**
     * @var SystemUploadCate
     */
    protected $model = null;

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
                'path'  => 'hpadmin/SystemUploadCate/add',
                'title' => '添加附件分类',
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
                'name'      => 'edit',
                'title'     => '编辑',
                'pageData'  => [
                    'path'  => 'hpadmin/SystemUploadCate/edit',
                    'title' => '附件分类修改',
                ],
                'style'     => []
            ],
            [
                'name'          => 'del',
                'title'         => '删除',
                'pageData'      => [
                    'path'      => 'hpadmin/SystemUploadCate/del',
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

    // 是否有分页
    public $paginate = true;

    // 验证器
    public $validate = [
        'class'             => \Hangpu8\Admin\validate\SystemUploadCate::class,
        'scene'             => [
            'add'           => 'add',
            'edit'          => 'edit',
        ],
    ];

    // 表格列
    public $columns = [
        'id'                => [
            'width'         => 100,
        ],
        'create_at'         => [],
        'title'             => [],
        'dir_name'          => [],
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
            'options'       => ['非系统', '系统'],
        ],
    ];

    // 表单添加视图列
    public $formAddColumns = [
        'title'             => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'dir_name'          => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'sort'               => [
            'type'          => 'input',
            'value'         => '0',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'is_system'         => [
            'type'          => 'select',
            'value'         => '0',
            'replace'       => '：0否，1是',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '非系统',
                        'value' => '0'
                    ],
                    [
                        'label' => '系统',
                        'value' => '1'
                    ],
                ],
            ],
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
        'dir_name'          => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'disabled'  => true,
            ],
        ],
        'sort'               => [
            'type'          => 'input',
            'value'         => '0',
            'replace'       => '',
            'save'          => true,
            'extra'         => [],
        ],
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemUploadCate;
    }

    /**
     * 添加数据前置回调
     *
     * @return void
     */
    public function formAddCallback(array $data): array
    {
        // 返回处理数据
        return $data;
    }

    /**
     * 修改数据前置回调
     *
     * @param array $data
     * @return array
     */
    public function formEditCallback(array $data): array
    {
        // 返回处理数据
        return $data;
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
            throw new Exception('系统附件分类，禁止删除');
        }
        return $model;
    }

    /**
     * 获取附件分类ID
     *
     * @param string $dir_name
     * @return integer
     */
    public static function verifyDirName(string $dir_name): int
    {
        $where = [
            'dir_name'      => $dir_name
        ];
        $model = SystemUploadCate::where($where)->first();
        if (!$model) {
            throw new Exception('该分类不存在');
        }
        return (int)$model->id;
    }
}
