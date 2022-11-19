<?php

namespace Hangpu8\Admin\controller;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Hangpu8\Admin\Base;
use Hangpu8\Admin\builder\FormBuilder;
use Hangpu8\Admin\builder\ListBuilder;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemAdmin;
use Hangpu8\Admin\utils\database\TableAction;
use Hangpu8\Admin\utils\Util;
use support\Request;

class ModulesController extends Base
{
    use Crud, TableAction;
    // 使用的模型
    protected $model = null;

    // 不允许删除表
    private $table_not_allow_drop = [
        'php_system_admin',
        'php_system_admin_log',
        'php_system_admin_role',
        'php_system_auth_rule',
        'php_system_config_group',
        'php_system_upload',
        'php_system_upload_cate',
        'php_system_webconfig',
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemAdmin;
    }

    /**
     * 数据表管理
     *
     * @return void
     */
    public function index(Request $request)
    {
        $field = $request->get('field', 'TABLE_NAME');
        $order = $request->get('order', 'asc');
        $allow_column = ['TABLE_NAME', 'TABLE_COMMENT', 'ENGINE', 'TABLE_ROWS', 'CREATE_TIME', 'UPDATE_TIME', 'TABLE_COLLATION'];
        if (!in_array($field, $allow_column)) {
            $field = 'TABLE_NAME';
        }
        $database = Util::getDatabase();
        // 获取所有表
        $tables = Util::db()->select("SELECT TABLE_NAME,TABLE_COMMENT,ENGINE,TABLE_ROWS,CREATE_TIME,UPDATE_TIME,TABLE_COLLATION FROM  information_schema.`TABLES` WHERE  TABLE_SCHEMA='{$database['database']}' order by $field $order");

        if ($tables) {
            $table_names = array_column($tables, 'TABLE_NAME');
            // 获取所有表记录数
            $table_rows_count = [];
            foreach ($table_names as $table_name) {
                $_table = str_replace($database['prefix'], '', $table_name);
                $table_rows_count[$table_name] = Util::db()->table($_table)->count();
            }
            foreach ($tables as $key => $table) {
                $tables[$key]->TABLE_ROWS = $table_rows_count[$table->TABLE_NAME] ?? $table->TABLE_ROWS;
            }
        }
        $builder = new ListBuilder;
        $data = $builder
            ->addColumn('rightButtonList', '操作', [
                'type'              => 'template',
                'width'             => 320,
                'template'          => 'rightButtonList'
            ])
            ->addTopButton('add', '创建表格', [
                'path'              => 'hpadmin/Modules/add',
                'title'             => '创建数据表',
                'method'            => 'get',
                'type'              => 'modal',
            ])
            ->addRightButton('crud', 'CRUD', [
                'path'              => 'hpadmin/Modules/makeCrud',
                'method'            => 'delete',
                'title'             => '温馨提示',
                'content'           => '是否确认创建CRUD代码?',
                'type'              => 'confirm',
                'rowPrefix'         => ['TABLE_NAME'],
            ], [
                'color'             => '#626aef',
            ])
            ->addRightButton('forms', '表单', [
                'path'              => 'hpadmin/Forms/index',
                'title'             => '表单管理',
                'method'            => 'get',
                'type'              => 'table',
                'rowPrefix'         => ['TABLE_NAME'],
            ])
            ->addRightButton('fields', '字段', [
                'path'              => 'hpadmin/Fields/index',
                'title'             => '表字段管理',
                'method'            => 'get',
                'type'              => 'table',
                'rowPrefix'         => ['TABLE_NAME'],
            ])
            ->addRightButton('edit', '修改', [
                'path'              => 'hpadmin/Modules/edit',
                'title'             => '修改数据表',
                'method'            => 'get',
                'type'              => 'modal',
                'rowPrefix'         => ['TABLE_NAME'],
            ])
            ->addRightButton('del', '删除', [
                'path'              => 'hpadmin/Modules/del',
                'method'            => 'delete',
                'title'             => '温馨提示',
                'content'           => '是否确认删除该数据？',
                'type'              => 'confirm',
                'rowPrefix'         => ['TABLE_NAME'],
            ], [
                'type'              => 'danger',
            ])
            ->addColumn('CREATE_TIME', '创建时间', [
                'width'             => 180
            ])
            ->addColumn('TABLE_NAME', '表名')
            ->addColumn('TABLE_ROWS', '记录数', [
                'width'             => 80
            ])
            ->addColumn('TABLE_COMMENT', '备注')
            ->addColumn('ENGINE', '引擎', [
                'width'             => 150
            ])
            ->addColumn('TABLE_COLLATION', '字符集', [
                'width'             => 180
            ])
            ->setData($tables)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 创建
     *
     * @return void
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            // 获取数据
            $post = $request->post();
            $table_name     = $post['table_name'];
            $table_comment  = $post['remarks'];
            $columns        = $this->getBaseicColumns();
            // 创建数据表
            Util::schema()->create($table_name, function (Blueprint $table) use ($columns) {
                $type_method_map = Util::methodControlMap();
                foreach ($columns as $column) {
                    if (!isset($column['type'])) {
                        throw new Exception("请为{$column['field']}选择类型");
                    }
                    if (!isset($type_method_map[$column['type']])) {
                        throw new Exception("不支持的类型{$column['type']}");
                    }
                    $this->createColumn($column, $table);
                }
                $table->charset     = 'utf8mb4';
                $table->collation   = 'utf8mb4_general_ci';
                $table->engine      = 'InnoDB';
            });
            // @todo 防注入
            $database = Util::getDatabase();
            Util::db()->statement("ALTER TABLE `{$database['prefix']}{$table_name}` COMMENT '$table_comment'");
            return parent::success('创建数据表成功');
        } else {
            $builder = new FormBuilder;
            $data = $builder
                ->setMethod('POST')
                ->addRow('table_name', 'input', '表名称', '', [
                    'col'               => [
                        'span'          => 12
                    ],
                ])
                ->addRow('remarks', 'input', '表备注', '', [
                    'col'               => [
                        'span'          => 12
                    ],
                ])
                ->create();
            return parent::successRes($data);
        }
    }

    /**
     * 修改
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        $table_name = $request->get('TABLE_NAME');
        $database = Util::getDatabase();
        if ($request->method() == 'PUT') {
            $post = $request->post();
            if (!$table_name) {
                throw new Exception('该数据表不存在');
            }
            if (in_array($table_name, $this->table_not_allow_drop)) {
                throw new Exception("{$table_name} 不允许修改");
            }
            // 改表名
            if ($table_name != $post['table_name']) {
                Util::checkTableName((string) $post['table_name']);
                $_table = str_replace($database['prefix'], '', $table_name);
                Util::schema()->rename($_table, $post['table_name']);
            }
            // @todo $table_comment 防止SQL注入
            Util::db()->statement("ALTER TABLE `$table_name` COMMENT '{$post['remarks']}'");

            // 返回结果
            return parent::success('修改数据表成功');
        } else {
            $table_schema = Util::db()->select("SELECT TABLE_COMMENT FROM  information_schema.`TABLES` WHERE  TABLE_SCHEMA='{$database['database']}' and TABLE_NAME='$table_name'");
            $table_comment = is_array($table_schema) ? current($table_schema) : '';
            if (!$table_comment) {
                throw new Exception('该数据表不存在');
            }
            $data['table_name']     = $table_name;
            $data['remarks']        = $table_comment->TABLE_COMMENT;
            $builder = new FormBuilder;
            $data = $builder
                ->setMethod('PUT')
                ->addRow('table_name', 'input', '表名称', '', [
                    'col'               => [
                        'span'          => 12
                    ],
                ])
                ->addRow('remarks', 'input', '表备注', '', [
                    'col'               => [
                        'span'          => 12
                    ],
                ])
                ->setFormData($data)
                ->create();
            return parent::successRes($data);
        }
    }

    /**
     * 删除
     *
     * @param Request $request
     * @return void
     */
    public function del(Request $request)
    {
        $table_name = $request->get('TABLE_NAME');
        if (!$table_name) {
            throw new Exception('该数据表不存在');
        }
        if (in_array($table_name, $this->table_not_allow_drop)) {
            throw new Exception("{$table_name} 不允许删除");
        }
        // 移除表前缀
        $database = Util::getDatabase();
        $_table = str_replace($database['prefix'], '', $table_name);
        // 删除表
        Util::schema()->drop($_table);
        return parent::success('删除数据表成功');
    }

    /**
     * 创建CRUD代码
     *
     * @return void
     */
    public function makeCrud()
    {
    }
}
