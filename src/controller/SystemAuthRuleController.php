<?php

namespace Hangpu8\Admin\controller;

use Hangpu8\Admin\Base;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemAuthRule;
use Hangpu8\Admin\utils\manager\DataMgr;
use support\Request;

class SystemAuthRuleController extends Base
{
    // 一键CRUD
    use Crud;

    // 筛选查询

    // 表格头部按钮
    public $topButton = [
        [
            'name'      => 'add',
            'title'     => '添加',
            'pageData'  => [
                'path'  => 'hpadmin/SystemAuthRule/add',
            ],
            'style'     => []
        ],
    ];

    // 验证器
    public $validate = [
        'class'             => \Hangpu8\Admin\validate\SystemAuthRule::class,
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
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'auth_type'         => [
            'type'          => 'radio',
            'value'         => '0',
            'replace'       => "：0否，1是",
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '否',
                        'value' => '0'
                    ],
                    [
                        'label' => '是',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'path'              => [
            'type'          => 'input',
            'replace'       => '：控制器/操作方法',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'pid'               => [
            'type'          => 'cascader',
            'value'         => [],
            'callback'      => [SystemAuthRuleController::class, 'getMenus'],
            'replace'       => '地址',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'showAllLevels' => false,
                'options'   => [],
                'props'                 => [
                    'props'             => [
                        'checkStrictly' => true,
                    ],
                ],
            ],
        ],
        'module'            => [
            'type'          => 'input',
            'value'         => 'admin',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'namespace'         => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'auth_params'       => [
            'type'          => 'input',
            'replace'       => "：remote/index，填写远程组件路径名称",
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'sort'              => [
            'type'          => 'input',
            'value'         => '0',
            'replace'       => '，值越大越靠后',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'method'            => [
            'type'          => 'checkbox',
            'value'         => ['GET'],
            'replace'       => '：GET,POST,PUT,DELETE',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => 'GET',
                        'value' => 'GET'
                    ],
                    [
                        'label' => 'POST',
                        'value' => 'POST'
                    ],
                    [
                        'label' => 'PUT',
                        'value' => 'PUT'
                    ],
                    [
                        'label' => 'DELETE',
                        'value' => 'DELETE'
                    ],
                ],
            ],
        ],
        'auth_rule'         => [
            'type'          => 'select',
            'value'         => 'form/index',
            'replace'       => "：layouts/index一级目录，'' 二级目录",
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '布局组件',
                        'value' => 'layouts/index'
                    ],
                    [
                        'label' => '没有组件',
                        'value' => ''
                    ],
                    [
                        'label' => '表单组件',
                        'value' => 'form/index'
                    ],
                    [
                        'label' => '表格组件',
                        'value' => 'table/index'
                    ],
                    [
                        'label' => '远程组件',
                        'value' => 'remote/index'
                    ],
                ],
            ],
        ],
        'show'              => [
            'type'          => 'radio',
            'value'         => '1',
            'replace'       => '：0隐藏，1显示（仅针对1-2级菜单）',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '隐藏',
                        'value' => '0'
                    ],
                    [
                        'label' => '显示',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'is_login'          => [
            'type'          => 'radio',
            'value'         => '1',
            'replace'       => ['需要', '：0否，1是'],
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '无需登录',
                        'value' => '0'
                    ],
                    [
                        'label' => '必须登录',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'icon'              => [
            'type'          => 'input',
            'replace'       => "",
            'save'          => true,
            'extra'         => [],
        ],
    ];

    // 是否处理表单渲染视图数据（函数名）
    public $formEditViewCheck = 'formEditViewCheck';

    // 表单修改视图列
    public $formEditColumns = [
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
        'auth_type'         => [
            'type'          => 'radio',
            'value'         => '0',
            'replace'       => "：0否，1是",
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '否',
                        'value' => '0'
                    ],
                    [
                        'label' => '是',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'path'              => [
            'type'          => 'input',
            'replace'       => '：控制器/操作方法',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'pid'               => [
            'type'          => 'cascader',
            'value'         => [],
            'callback'      => [SystemAuthRuleController::class, 'getMenus'],
            'replace'       => '地址',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'showAllLevels' => false,
                'options'   => [],
                'props'                 => [
                    'props'             => [
                        'checkStrictly' => true,
                    ],
                ],
            ],
        ],
        'module'            => [
            'type'          => 'input',
            'value'         => 'admin',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'namespace'         => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'auth_params'       => [
            'type'          => 'input',
            'replace'       => "：remote/index，填写远程组件路径名称",
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'sort'              => [
            'type'          => 'input',
            'value'         => '0',
            'replace'       => '，值越大越靠后',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'method'            => [
            'type'          => 'checkbox',
            'value'         => ['GET'],
            'replace'       => '：GET,POST,PUT,DELETE',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => 'GET',
                        'value' => 'GET'
                    ],
                    [
                        'label' => 'POST',
                        'value' => 'POST'
                    ],
                    [
                        'label' => 'PUT',
                        'value' => 'PUT'
                    ],
                    [
                        'label' => 'DELETE',
                        'value' => 'DELETE'
                    ],
                ],
            ],
        ],
        'auth_rule'         => [
            'type'          => 'select',
            'value'         => 'form/index',
            'replace'       => "：layouts/index一级目录，'' 二级目录",
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '布局组件',
                        'value' => 'layouts/index'
                    ],
                    [
                        'label' => '没有组件',
                        'value' => ''
                    ],
                    [
                        'label' => '表单组件',
                        'value' => 'form/index'
                    ],
                    [
                        'label' => '表格组件',
                        'value' => 'table/index'
                    ],
                    [
                        'label' => '远程组件',
                        'value' => 'remote/index'
                    ],
                ],
            ],
        ],
        'show'              => [
            'type'          => 'radio',
            'value'         => '1',
            'replace'       => '：0隐藏，1显示（仅针对1-2级菜单）',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '隐藏',
                        'value' => '0'
                    ],
                    [
                        'label' => '显示',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'is_login'          => [
            'type'          => 'radio',
            'value'         => '1',
            'replace'       => ['需要', '：0否，1是'],
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '无需登录',
                        'value' => '0'
                    ],
                    [
                        'label' => '必须登录',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'icon'              => [
            'type'          => 'input',
            'replace'       => "",
            'save'          => true,
            'extra'         => [],
        ],
    ];

    /**
     * @var SystemAuthRule
     */
    protected $model = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemAuthRule;
    }

    /**
     * 显示菜单列表
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $model = $this->model;
        $builder = new \Hangpu8\Admin\builder\ListBuilder;
        $data = $builder
            ->addColumn('rightButtonList', '操作', [
                'type'              => 'template',
                'width'             => 150,
                'template'          => 'rightButtonList'
            ])
            ->addTopButton('add', '添加', [
                'path'              => 'hpadmin/SystemAuthRule/add',
                'title'             => '添加权限菜单',
                'method'            => 'get',
                'type'              => 'modal',
            ])
            ->addRightButton('edit', '编辑', [
                'path'              => 'hpadmin/SystemAuthRule/edit',
                'title'             => '修改权限菜单',
                'method'            => 'get',
                'type'              => 'modal',
                'rowPrefix'         => ['path'],
            ])
            ->addRightButton('del', '删除', [
                'path'              => 'hpadmin/SystemAuthRule/del',
                'method'            => 'delete',
                'title'             => '温馨提示',
                'content'           => '是否确认删除该数据？',
                'type'              => 'confirm',
                'rowPrefix'         => ['path'],
            ], [
                'type'              => 'danger',
            ])
            ->setChildren([
                'field'             => 'path',
            ])
            ->addColumn('module', '模块名称')
            ->addColumn('path', '菜单地址')
            ->addColumn('title', '菜单名称')
            ->addColumn('sort', '菜单排序', [
                'width'             => 80,
            ])
            ->addColumn('method', '请求类型', [
                'width'             => 160
            ])
            ->addColumn('auth_type', '设置接口', [
                'type'              => 'tag',
                'width'             => 80,
                'options'           => ['否', '是'],
                'style'             => [
                    [
                        'type'      => 'warning',
                    ],
                    [
                        'type'      => 'success',
                    ],
                ],
            ])
            ->addColumn('show', '是否显示', [
                'type'              => 'tag',
                'width'             => 80,
                'options'           => ['隐藏', '显示'],
                'style'             => [
                    [
                        'type'      => 'warning',
                    ],
                    [
                        'type'      => 'success',
                    ],
                ],
            ])
            ->addColumn('is_login', '是否登录', [
                'type'              => 'tag',
                'width'             => 100,
                'options'           => ['无需登录', '必须登录'],
                'style'             => [
                    [
                        'type'      => 'warning',
                    ],
                    [
                        'type'      => 'success',
                    ],
                ],
            ]);
        // 设置数据
        $list = $model->order('sort', 'asc')
            ->select()
            ->each(function ($item) {
                return $item;
            })->toArray();
        $list = DataMgr::channelLevel($list, '', '', 'path');
        $list = self::formatData($list);
        $builder = $builder->setData($list);

        // 获取规则
        $data = $builder->create();

        // 返回数据
        return parent::successRes($data);
    }

    /**
     * 添加数据前置回调
     *
     * @param array $data
     * @return array
     */
    public static function formAddCallback(array $data): array
    {
        $data['pid'] = is_array($data['pid']) ? end($data['pid']) : $data['pid'];
        $data['path'] = ucfirst($data['path']);
        $data['method'] = implode(',', $data['method']);
        return $data;
    }

    /**
     * 修改数据前置回调
     *
     * @param array $data
     * @return array
     */
    public static function formEditCallback(array $data): array
    {
        $data['pid'] = is_array($data['pid']) ? end($data['pid']) : $data['pid'];
        $data['path'] = ucfirst($data['path']);
        $data['method'] = implode(',', $data['method']);
        return $data;
    }

    /**
     * 获取菜单数据
     *
     * @return void
     */
    public static function getMenus(array $data): array
    {
        $field = 'path as value,title as label,pid';
        $authRule = SystemAuthRule::order('sort asc,id asc')
            ->field($field)
            ->select()
            ->toArray();
        $ruleData = DataMgr::channelLevel($authRule, '', '', 'value');
        $ruleData = self::formatData($ruleData);
        $parent = [
            'pid'       => '',
            'value'     => '',
            'label'     => '顶级菜单'
        ];
        array_unshift($ruleData, $parent);

        $data['extra']['options'] = $ruleData;

        return $data;
    }

    /**
     * 处理编辑视图回显数据
     *
     * @param array $data
     * @return array
     */
    public static function formEditViewCheck(array $data): array
    {
        $data['method'] = explode(',', $data['method']);
        return $data;
    }

    /**
     * 获取格式化数据
     *
     * @param array $data
     * @return array
     */
    public static function formatData(array $data): array
    {
        $list = [];
        $i = 0;
        foreach ($data as $value) {
            $list[$i] = $value;
            if (isset($value['children']) && $value['children']) {
                $list[$i]['children'] = self::formatData($value['children']);
            }
            $i++;
        }
        return $list;
    }

    /**
     * 获取默认权限规则
     *
     * @return array
     */
    public static function getDefaultRule(): array
    {
        $where = [
            ['is_default', '=', 1],
        ];
        $data = SystemAuthRule::where($where)->column('path');
        return $data;
    }
}
