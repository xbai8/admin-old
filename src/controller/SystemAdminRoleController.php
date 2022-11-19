<?php

namespace Hangpu8\Admin\controller;

use Exception;
use Hangpu8\Admin\Base;
use Hangpu8\Admin\builder\FormBuilder;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemAdminRole;
use Hangpu8\Admin\model\SystemAuthRule;
use Hangpu8\Admin\utils\manager\DataMgr;
use support\Request;

class SystemAdminRoleController extends Base
{
    // 一键CRUD
    use Crud;

    /**
     * @var SystemAdminRole
     */
    protected $model = null;

    // 事件定义
    public $crudEvent = [
        'tableEventBefore'  => 'tableDataCallback',
        'delEventBefore'    => 'delBefore',
    ];

    // 表格头部按钮
    public $topButton = [
        [
            'name'      => 'add',
            'title'     => '添加',
            'pageData'  => [
                'path'  => 'hpadmin/SystemAdminRole/add',
            ],
            'style'     => []
        ],
    ];

    // 表格列右侧按钮
    public $rightButton = [
        'title'         => '操作选项',
        'extra'         => [
            'template'  => 'rightButtonList',
            'width'     => 200
        ],
        'button'        => [
            [
                'name'      => 'auth',
                'title'     => '权限',
                'pageData'  => [
                    'path'  => 'hpadmin/SystemAdminRole/auth',
                    'title' => '部门权限',
                ],
                'style'     => [
                    'type'  => 'primary',
                ]
            ],
            [
                'name'      => 'edit',
                'title'     => '编辑',
                'pageData'  => [
                    'path'  => 'hpadmin/SystemAdminRole/edit',
                ],
                'style'     => []
            ],
            [
                'name'          => 'del',
                'title'         => '删除',
                'pageData'      => [
                    'path'      => 'hpadmin/SystemAdminRole/del',
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
        'class'             => \Hangpu8\Admin\validate\SystemAdminRole::class,
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
        'create_at'         => [
            'width'         => 180
        ],
        'title'             => [],
        'is_system'         => [
            'type'          => 'tag',
            'width'         => 100,
            'replace'       => '：0不是系统，1是系统',
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

    // 表单添加视图列
    public $formAddColumns = [
        'title'             => [
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
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemAdminRole;
    }

    /**
     * 表格前置事件
     *
     * @return void
     */
    public function tableDataCallback(Request $request)
    {
        $model = $this->model;
        $admin = hp_admin(['role_id']);
        $where = [
            ['pid', '=', $admin['role_id']],
        ];
        $model = $model->where($where);
        $model = $model->orderBy('id', 'desc');

        // 返回构造模型
        return $model;
    }

    /**
     * 添加数据前置回调
     *
     * @return void
     */
    public function formAddCallback(array $data): array
    {
        $admin = hp_admin(['role_id']);
        $data['pid'] = $admin['role_id'];
        // 获取默认权限规则
        $rule = SystemAuthRuleController::getDefaultRule();
        $data['rule'] = implode(',', $rule);

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
            throw new Exception('系统部门，禁止删除');
        }
        $admin = hp_admin(['role_id']);
        if ($model->pid != $admin['role_id']) {
            throw new Exception('该部门非您的旗下');
        }
        return $model;
    }

    /**
     * 部门权限设置
     *
     * @return void
     */
    public function auth(Request $request)
    {
        $id = $request->get('id');
        $model = $this->model;

        $where = [
            'id'        => $id,
        ];
        $roleModel = $model->where($where)->first();
        if (!$roleModel) {
            throw new Exception('找不到该数据');
        }
        $method = 'PUT';
        if ($request->method() == $method) {
            $post = $request->post();
            if (!isset($post['rule']) || !is_array($post['rule'])) {
                throw new Exception('设置权限错误');
            }
            $roleModel->rule = implode(',', $post['rule']);
            if (!$roleModel->save()) {
                throw new Exception('设置权限失败');
            }
            return parent::success('设置权限成功');
        } else {
            $roleData = $roleModel->toArray();
            $visible = [
                'path',
                'title',
                'pid',
                'is_default'
            ];
            $rule = SystemAuthRule::orderBy('sort', 'asc')
                ->get()
                ->toArray();
            $rule = self::fieldMap($rule, 'path', 'id');
            $authRule = DataMgr::channelLevel($rule, '0', '', 'id');
            $authRule = SystemAuthRuleController::formatData($authRule);
            // 解析权限规则
            $roleData['rule'] = $roleData['rule'] ? explode(',', $roleData['rule']) : [];
            $builder = new FormBuilder;
            $data = $builder
                ->setMethod($method)
                ->addCustom('title', 'info', '部门名称')
                ->addRow('rule', 'tree', '权限授权', [], [
                    'data'                      => $authRule,
                    'showCheckbox'              => true,
                    'defaultExpandAll'          => true
                ])
                ->setFormData($roleData)
                ->create();
            return parent::successRes($data);
        }
    }

    /**
     * 获取部门选项
     *
     * @param array $column
     * @return array
     */
    public static function getRoleOptions(array $data): array
    {
        $field = ['id as value', 'title as label'];
        $options = SystemAdminRole::orderBy('id', 'desc')
            ->select($field)
            ->get()
            ->each(function ($item) {
                return $item;
            })->toArray();
        $data['extra']['options'] = $options;
        return $data;
    }

    /**
     * 映射字段
     *
     * @param array $data
     * @param string $field
     * @param string $field2
     * @return array
     */
    private static function fieldMap(array $data, string $field, string $field2): array
    {
        $list = [];
        foreach ($data as $key => $value) {
            $value['disabled'] = false;
            if ($value['is_default'] == '1') {
                $value['disabled'] = true;
            }
            $value[$field2] = $value[$field];
            $list[$key] = $value;
        }
        return $list;
    }
}
