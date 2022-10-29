<?php

namespace Hangpu8\Admin\install;

use Hangpu8\Admin\Base;

/**
 * @title 环境检测
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Environment extends Base
{
    /**
     * 检测函数是否启用
     *
     * @return array
     */
    public static function checkFun(): array
    {
        $data = [
            [
                'name'              => 'GD库',
                'result'            => get_extension_funcs("gd") ? true : false,
                'value'             => get_extension_funcs("gd") ? '已开启' : '未开启',
            ],
        ];
        return $data;
    }

    /**
     * 检测目录权限
     *
     * @return void
     */
    public static function dirAuth()
    {
        $dirAuth = [
            [
                'path'              => base_path() . "/public",
                'name'              => "/public",
            ],
            [
                'path'              => base_path() . "/runtime",
                'name'              => "/runtime",
            ],
            [
                'path'              => base_path() . "/vendor",
                'name'              => "/vendor",
            ],
        ];
        $data = [];
        foreach ($dirAuth as $key => $value) {
            $data[$key]['name'] = $value['name'];
            $data[$key]['result'] = is_writable($value['path']) ? true : false;
            $data[$key]['value'] = is_writable($value['path']) ? '可写入' : '不可写入';
        }
        return $data;
    }

    /**
     * 获得服务器信息
     *
     * @return void
     */
    public static function getServer()
    {
        $host = request()->host();
        $data = [
            [
                'name'              => '应用域名',
                'result'            => true,
                'value'             => "http://{$host}/",
            ],
            [
                'name'              => '操作系统',
                'result'            => true,
                'value'             => PHP_OS,
            ],
            [
                'name'              => 'PHP版本',
                'result'            => !version_compare(PHP_VERSION, '7.2.5', '<'),
                'value'             => PHP_VERSION . "（推荐8.0，最低7.4.0）",
            ],
            [
                'name'              => '安装目录',
                'result'            => true,
                'value'             => str_replace('\\', '/', base_path()),
            ],
        ];
        return $data;
    }
}
