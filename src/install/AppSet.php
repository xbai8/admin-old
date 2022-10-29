<?php

namespace Hangpu8\Admin\install;

use Exception;
use Hangpu8\Admin\Base;

/**
 * @title 数据库设置
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class AppSet extends Base
{
    /**
     * 站点表单信息
     *
     * @return array
     */
    public static function getSiteInfo(): array
    {
        $web_name       = "HPAdmin";
        $http           = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
        $host           = request()->host();
        $web_url        = "{$http}://{$host}/";
        $data           = [
            [
                'field'             => 'web_name',
                'type'              => 'text',
                'name'              => '应用名称',
                'value'             => $web_name,
                'tip'               => '示例：HPAdmin 或 行铺网络',
            ],
            [
                'field'             => 'web_url',
                'type'              => 'text',
                'name'              => '应用域名',
                'value'             => $web_url,
                'tip'               => '示例：http://hpadmin.hangpu.net，结尾不带斜杠，接口处可能经常用到',
            ],
        ];
        return $data;
    }

    /**
     * 管理员配置
     *
     * @return array
     */
    public static function getAdminConfig(): array
    {
        $data = [
            [
                'field'             => 'username',
                'type'              => 'text',
                'name'              => '登录账号',
                'value'             => '',
                'tip'               => '后台系统管理员账号，不允许删除',
            ],
            [
                'field'             => 'password',
                'type'              => 'password',
                'name'              => '登录密码',
                'value'             => '',
                'tip'               => '系统管理员登录密码',
            ],
            [
                'field'             => 'confirm_pass',
                'type'              => 'password',
                'name'              => '重复密码',
                'value'             => '',
                'tip'               => '请在一次输入登录密码',
            ],
        ];
        return $data;
    }

    /**
     * 获取数据库配置参数
     *
     * @return array
     */
    public static function getMysqlConfig(): array
    {
        $data = [
            [
                'field'             => 'type',
                'name'              => '数据库类型',
                'value'             => 'mysql',
            ],
            [
                'field'             => 'host',
                'name'              => '数据库地址',
                'value'             => '127.0.0.1',
            ],
            [
                'field'             => 'port',
                'name'              => '数据库端口',
                'value'             => '3306',
            ],
            [
                'field'             => 'user',
                'name'              => '数据库用户',
                'value'             => '',
            ],
            [
                'field'             => 'database',
                'name'              => '数据库名称',
                'value'             => '',
            ],
            [
                'field'             => 'password',
                'name'              => '数据库密码',
                'value'             => '',
            ],
            [
                'field'             => 'prefix',
                'name'              => '数据表前缀',
                'value'             => 'php_',
            ],
        ];
        return $data;
    }

    /**
     * 获取数据库类型
     *
     * @return array
     */
    public static function getMysqlType(): array
    {
        $data = [
            [
                'name'              => 'mysql',
                'value'             => 'mysql',
            ],
        ];
        return $data;
    }

    /**
     * 验证提交数据
     *
     * @param array $post
     * @return void
     */
    public static function verifyData(array $post)
    {
        $mysqlList = isset($post['mysqlList']) ? $post['mysqlList'] : '';
        $sitePost = isset($post['site']) ? $post['site'] : '';
        $adminPost = isset($post['admin']) ? $post['admin'] : '';
        // 数据验证
        if (!isset($mysqlList[0]['value']) || empty($mysqlList[0]['value'])) {
            throw new Exception('请选择数据库类型');
        }
        if (!isset($mysqlList[1]['value']) || empty($mysqlList[1]['value'])) {
            throw new Exception('请输入数据库地址');
        }
        if (!isset($mysqlList[2]['value']) || empty($mysqlList[2]['value'])) {
            throw new Exception('请输入数据库端口');
        }
        if (!isset($mysqlList[3]['value']) || empty($mysqlList[3]['value'])) {
            throw new Exception('请输入数据库用户');
        }
        if (!isset($mysqlList[4]['value']) || empty($mysqlList[4]['value'])) {
            throw new Exception('请输入数据库名称');
        }
        if (!isset($mysqlList[5]['value']) || empty($mysqlList[5]['value'])) {
            throw new Exception('请输入数据库密码');
        }
        if (!isset($mysqlList[6]['value']) || empty($mysqlList[6]['value'])) {
            throw new Exception('请输入表前缀');
        }
        // 站点数据安装验证
        if (!isset($sitePost[0]['value']) || empty($sitePost[0]['value'])) {
            throw new Exception('请输入应用名称');
        }
        if (!isset($sitePost[1]['value']) || empty($sitePost[1]['value'])) {
            throw new Exception('请输入应用域名');
        }
        // 管理员验证
        if (!isset($adminPost[0]['value']) || empty($adminPost[0]['value'])) {
            throw new Exception('请输入登录账号');
        }
        if (strlen($adminPost[0]['value']) < 5 || strlen($adminPost[0]['value']) > 16) {
            throw new Exception('登录账号5-16个字符');
        }
        if (!isset($adminPost[1]['value']) || empty($adminPost[1]['value'])) {
            throw new Exception('请输入登录密码');
        }
        if (strlen($adminPost[1]['value']) < 6 || strlen($adminPost[0]['value']) > 20) {
            throw new Exception('登录密码6-20位数字');
        }
        if (!isset($adminPost[2]['value']) || empty($adminPost[2]['value'])) {
            throw new Exception('请再次输入登录密码');
        }
        if ($adminPost[1]['value'] != $adminPost[2]['value']) {
            throw new Exception('两次密码输入不一致');
        }
        // 连接数据库验证
        try {
            $host = $mysqlList[1]['value'];
            $user = $mysqlList[3]['value'];
            $password = $mysqlList[5]['value'];
            $port = $mysqlList[2]['value'];
            $database = $mysqlList[4]['value'];
            $db = AppDatabase::getPdo($host, $user, $password, $port);
            $db->exec("use {$database}");
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
    }
}
