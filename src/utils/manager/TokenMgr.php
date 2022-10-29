<?php

namespace Hangpu8\Admin\utils\manager;

use Exception;

/**
 * @title 加密/解密管理器
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class TokenMgr
{
    /**
     * 创建令牌授权
     *
     * @param integer $uid 用户ID
     * @param string $platform 平台类型：web，android，ios，douyin，wechat
     * @param string $device 用户设备ID
     * @param integer $expire 过期时间，默认：30天
     * @return string
     */
    public static function _create_token(int $uid, string $platform, string $device, $expire = 2592000): string
    {
        $data['uid']            = $uid;
        $data['platform']       = $platform;
        $data['device']         = $device;
        $data['expire_time']    = time() + $expire;
        $tokenData = json_encode($data, 256);
        $token = self::_encrypt($tokenData);
        return $token;
    }

    /**
     * 解密为完整token
     *
     * @param string $token
     * @return array
     */
    public static function _decrypt_token(string $token): array
    {
        $tokenData = self::_decrypt($token);
        if (!$tokenData) {
            throw new Exception('令牌签名错误');
        }
        $token = json_decode($tokenData, true);
        return $token;
    }

    /**
     * 系统加密方法
     *
     * @param string $data 要加密的字符串
     * @param string $key 加密密钥
     * @param integer $expire 过期时间，单位：秒
     * @return string
     */
    private static function _encrypt(string $data, string $key = '', int $expire = 0): string
    {
        $key  = md5(empty($key) ? 'hangpu' : $key);
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }

        $str = sprintf('%010d', $expire ? $expire + time() : 0);

        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
        }
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
    }

    /**
     * 系统解密方法
     *
     * @param string $data 要解密的字符串 （必须是_encrypt方法加密的字符串）
     * @param string $key 加密密钥
     * @return string
     */
    private static function _decrypt(string $data, string $key = ''): string
    {
        $key    = md5(empty($key) ? 'hangpu' : $key);
        $data   = str_replace(array('-', '_'), array('+', '/'), $data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $expire = substr($data, 0, 10);
        $data   = substr($data, 10);

        if ($expire > 0 && $expire < time()) {
            return '';
        }
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return base64_decode($str);
    }
}
