<?php

namespace Hangpu8\Admin\controller;

use Exception;
use Hangpu8\Admin\Base;
use Hangpu8\Admin\crud\Crud;
use Hangpu8\Admin\model\SystemUpload;
use Shopwwi\WebmanFilesystem\Facade\Storage;
use support\Request;

/**
 * @title 附件管理
 * @desc 默认使用插件：https://github.com/shopwwi/webman-filesystem
 * @author 楚羽幽 <admin@hangpu.net>
 */
class SystemUploadController extends Base
{
    // 一键CRUD
    use Crud;

    /**
     * @var SystemUpload
     */
    protected $model = null;

    // 图片后缀类型
    public $imageExt = ['jpg', 'gif', 'png'];

    // 事件定义
    public $crudEvent = [
        'delEventBefore'    => 'delBefore',
    ];

    // 表格列右侧按钮
    public $rightButton = [
        'title'         => '操作选项',
        'extra'         => [
            'template'  => 'rightButtonList',
            'width'     => 100
        ],
        'button'        => [
            [
                'name'          => 'del',
                'title'         => '删除',
                'pageData'      => [
                    'path'      => 'hpadmin/SystemUpload/del',
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
        'class'             => \Hangpu8\Admin\validate\SystemAdmin::class,
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
        'cid'               => [
            'width'         => 180
        ],
        'title'             => [],
        'filename'          => [],
        'format'            => [
            'width'         => 80
        ],
        'size'              => [
            'width'         => 120
        ],
    ];

    // 表单添加视图列
    public $formAddColumns = [
        'username'          => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'password'          => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'nickname'          => [
            'type'          => 'input',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'role_id'           => [
            'type'          => 'input',
            'type'          => 'select',
            'callback'      => [SystemAdminRoleController::class, 'getRoleOptions'],
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
            ],
        ],
        'status'            => [
            'type'          => 'radio',
            'value'         => '1',
            'replace'       => '：0禁用，1启用',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => [
                    [
                        'label' => '禁用',
                        'value' => '0'
                    ],
                    [
                        'label' => '正常',
                        'value' => '1'
                    ],
                ],
            ],
        ],
        'headimg'           => [
            'type'          => 'upload',
            'replace'       => '',
            'save'          => true,
            'extra'         => [
                'col'       => [
                    'span'  => 12
                ],
                'props'     => [
                    'modalTitle'    => '图片预览',
                    'listType'      => 'picture-card',
                    'action'        => 'https://jsonplaceholder.typicode.com/posts/',
                ],
            ],
        ],
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemUpload;
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
        $model = $model->where($where)->find();
        return $model;
    }

    /**
     * 上传附件
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $dir_name = $request->post('dir_name', 'system_file');

        // 验证分类是否存在
        $cid = SystemUploadCateController::verifyDirName($dir_name);

        // 获取上传配置
        $config = self::getConfig();
        // 默认最大上传100M
        $max_size = isset($config['max_size']) ? $config['max_size'] : 1024 * 1024 * 100;
        // 允许上传文件类型 为空则为允许所有
        $ext_yes = isset($config['ext_yes']) ? $config['ext_yes'] : [];
        // 不允许上传文件类型 为空则不限制
        $ext_no = isset($config['ext_no']) ? $config['ext_no'] : [];

        // 保存文件至硬盘或云端
        $result = Storage::path("uploads/{$dir_name}")
            ->size($max_size)
            ->extYes($ext_yes)
            ->extNo($ext_no)
            ->upload($file, false);

        // 储存附件
        $data = [
            'cid'       => $cid,
            'path'      => $result->file_name,
            'filename'  => basename($result->file_name),
            'title'     => basename($result->file_name),
            'format'    => $result->extension,
            'size'      => get_size($result->size),
            'adapter'   => $result->adapter,
            'width'     => 0,
            'height'    => 0,
        ];
        // 如果是图片，设置尺寸
        if (in_array($result->extension, $this->imageExt)) {
            $data['width'] = $result->file_width;
            $data['height'] = $result->file_height;
        }
        // 保存附件
        $this->saveUpload($data);
        // 返回URL
        $response = [
            'url'       => $result->file_url
        ];
        return parent::successRes($response);
    }

    /**
     * 获取上传配置
     *
     * @return array
     */
    public static function getConfig(): array
    {
        return \config('plugin.shopwwi.filesystem.app');
    }

    /**
     * 获取默认选定器
     *
     * @return array
     */
    public static function getStorageConfig(): array
    {
        $confog = self::getConfig();
        $default = isset($confog['default']) ? $confog['default'] : 'public';
        $storage = isset($confog['storage']) ? $confog['storage'] : [];
        return isset($storage[$default]) ? $storage[$default] : [];
    }

    /**
     * 替换上传地址为路径
     *
     * @param string $url
     * @return string
     */
    public static function urlReplace(string $url): string
    {
        $storageConfig = self::getStorageConfig();
        if (!isset($storageConfig['url'])) {
            throw new Exception('替换地址错误，没有找到配置的URL');
        }
        $path = str_replace("{$storageConfig['url']}/", '', $url);
        return $path;
    }

    /**
     * 保存附件
     *
     * @param array $data
     * @return void
     */
    private function saveUpload(array $data)
    {
        $model = $this->model;
        $where = [
            'filename'      => $data['filename']
        ];
        $fileCount = $model->where($where)->count();
        if ($fileCount) {
            return;
        }
        if (!$model->save($data)) {
            throw new Exception('附件保存失败');
        }
    }
}
