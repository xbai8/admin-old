<?php

namespace Hangpu8\Admin\utils;

use Exception;

/**
 * @title 获取上传配置
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Upload
{
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
}
