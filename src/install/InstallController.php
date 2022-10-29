<?php

namespace Hangpu8\Admin\install;

use Exception;
use Hangpu8\Admin\Base;

/**
 * @title Admin安装业务
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class InstallController extends Base
{
    // 安装步骤
    private static $nextStep = [
        'database'      => 'site',
        'site'          => 'admin',
        'admin'         => 'complete',
        'complete'      => '',
    ];
    // 安装步骤文字
    private static $stepText = [
        'database'      => '安装数据库结构中...',
        'site'          => '安装应用信息成功...',
        'admin'         => '安装管理员信息成功...',
        'complete'      => '安装完成，正在跳转中...',
    ];

    /**
     * 跳转安装界面
     *
     * @return void
     */
    public function index()
    {
        return redirect('/install/view/');
    }

    /**
     * 安装信息
     *
     * @return void
     */
    public function step1()
    {
        $filePath = str_replace("\\", '/', __DIR__);
        $content = file_get_contents("{$filePath}/tpl/agreement.txt");
        return parent::successRes([
            'content'       => $content,
        ]);
    }

    /**
     * 环境检测
     *
     * @return void
     */
    public function step2()
    {
        $server = Environment::getServer();
        $fun = Environment::checkFun();
        $dirAuth = Environment::dirAuth();
        $data = [
            'server'                => $server,
            'fun'                   => $fun,
            'dir'                   => $dirAuth
        ];
        return parent::successRes($data);
    }

    /**
     * 数据库设置
     *
     * @return void
     */
    public function step3()
    {
        if (request()->method() == 'POST') {
            // 验证数据
            $post = request()->post();
            AppSet::verifyData($post);
            return parent::success('验证通过');
        } else {
            $data = [
                'mysqlType'             => AppSet::getMysqlType(),
                'mysqlList'             => AppSet::getMysqlConfig(),
                'site'                  => AppSet::getSiteInfo(),
                'admin'                 => AppSet::getAdminConfig(),
            ];
            return parent::successRes($data);
        }
    }

    /**
     * 开始安装
     *
     * @return void
     */
    public function step4()
    {
        $step = request()->post('step');
        $post = request()->post();
        if ($step === '') {
            throw new Exception('安装步骤错误');
        }
        if ($post === '') {
            throw new Exception('安装数据错误');
        }
        if (!isset($post['database'])) {
            throw new Exception('数据连接错误');
        }
        // 获取数据连接信息
        $database = $post['database'];
        $site = $post['site'];
        $admin = $post['admin'];
        // 连接数据库
        try {
            $db = AppDatabase::getPdo($database['host'], $database['user'], $database['password'], $database['port']);
            $smt = $db->query("show databases like '{$database['database']}'");
            if (empty($smt->fetchAll())) {
                $db->exec("create database {$database['database']}");
            }
            $db->exec("use {$database['database']}");
            $smt = $db->query("show tables");
        } catch (\Throwable $e) {
            if (stripos($e, 'Access denied for user')) {
                throw new Exception('数据库用户名或密码错误');
            }
            if (stripos($e, 'Connection refused')) {
                throw new Exception('请确认数据库IP端口是否正确，数据库已经启动');
            }
            if (stripos($e, 'timed out')) {
                throw new Exception('数据库连接超时，请确认数据库IP端口是否正确，安全组及防火墙已经放行端口');
            }
            throw $e;
        }
        $dateTime = date('Y-m-d H:i:s');
        // 执行安装
        switch ($step) {
                // 安装数据库结构
            case 'database':
                $sql_file = str_replace("\\", '/', __DIR__) . '/sql/database.sql';
                $sql_query = file_get_contents($sql_file);
                $sql_query = AppDatabase::removeComments($sql_query);
                $sql_query = AppDatabase::splitSqlFile($sql_query, ';');
                foreach ($sql_query as $sql) {
                    $db->exec(AppDatabase::strReplacePrefix($sql, $database['prefix']));
                }
                break;
                // 写入应用配置
            case 'site':
                $data = [
                    [
                        'create_at'     => $dateTime,
                        'update_at'     => $dateTime,
                        'cid'           => 1,
                        'title'         => '应用名称',
                        'name'          => 'web_name',
                        'value'         => $site['web_name'],
                        'type'          => 'input',
                        'extra'         => '',
                        'placeholder'   => '请输入应用名称',
                    ],
                    [
                        'create_at'     => $dateTime,
                        'update_at'     => $dateTime,
                        'cid'           => 1,
                        'title'         => '应用域名',
                        'name'          => 'web_url',
                        'value'         => $site['web_url'],
                        'type'          => 'input',
                        'extra'         => '',
                        'placeholder'   => '请输入应用网址，带斜杠结尾',
                    ],
                ];
                foreach ($data as $key => $item) {
                    $smt = $db->prepare("insert into `{$database['prefix']}system_webconfig` (`create_at`, `update_at`, `cid`, `title`, `name`, `value`, `type`, `extra`, `placeholder`) values (:create_at, :update_at, :cid, :title, :name, :value, :type, :extra, :placeholder)");
                    foreach ($item as $field => $value) {
                        $smt->bindValue($field, $value);
                    }
                    if (!$smt->execute()) {
                        throw new Exception('安装应用信息失败...');
                    }
                }
                break;
                // 写入管路员信息
            case 'admin':
                $smt = $db->prepare("insert into `{$database['prefix']}system_admin` (`create_at`, `update_at`, `role_id`, `pid`, `username`, `password`, `nickname`) values (:create_at, :update_at, :role_id, :pid, :username, :password, :nickname)");
                $data = [
                    'create_at'     => $dateTime,
                    'update_at'     => $dateTime,
                    'role_id'       => 1,
                    'pid'           => 0,
                    'username'      => $admin['admin_name'],
                    'password'      => md5($admin['password']),
                    'nickname'      => 'HPAdmin',
                ];
                foreach ($data as $key => $value) {
                    $smt->bindValue($key, $value);
                }
                if (!$smt->execute()) {
                    throw new Exception('安装管理员失败...');
                }
                break;
            case 'complete':
                break;
        }
        $response = [
            'text'  => self::$stepText[$step],
            'next'  => self::$nextStep[$step],
        ];
        return parent::successRes($response);
    }

    /**
     * 安装完成
     *
     * @return void
     */
    public function step5()
    {
        if (request()->method() == 'POST') {
            $step = request()->post('step');
            $database = request()->post('database');
            if (!$database) {
                throw new Exception('安装失败');
            }
            // 安装文件
            $env_file = str_replace("\\", '/', __DIR__) . '/tpl/.env';
            $env_string = file_get_contents($env_file);
            $envStrArray1 = [
                '{hostname}',
                '{database}',
                '{user}',
                '{password}',
                '{port}',
                '{prefix}',
            ];
            $envStrArray2 = [
                $database['host'],
                $database['database'],
                $database['user'],
                $database['password'],
                $database['port'],
                $database['prefix'],
            ];
            $_string = str_replace($envStrArray1, $envStrArray2, $env_string);
            $write_file = base_path() . '/.env';
            // 写入配置文件
            file_put_contents($write_file, $_string);
            $response = [
                'text'  => self::$stepText[$step],
                'next'  => self::$nextStep[$step],
            ];
            return parent::successRes($response);
        } else {
            $html = "<div>恭喜你，应用安装成功</div>";
            $html .= "<div style='padding-top:10px;color:red;'>务必重启Webman服务后再访问</div>";
            $data = [
                'content'       => $html,
            ];
            return parent::successRes($data);
        }
    }
}
