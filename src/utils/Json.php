<?php

namespace Hangpu8\Admin\utils;

use support\Response;

/**
 * @title JSON返回
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait Json
{

    /**
     * 返回JSON数据
     *
     * @param string $msg
     * @param integer $code
     * @param array $data
     * @return Response
     */
    public static function json(string $msg, int $code, array $data = []): Response
    {
        $json['msg'] = $msg;
        $json['code'] = $code;
        $json['data'] = $data;
        return json($json);
    }

    /**
     * 返回成功消息
     *
     * @param string $msg
     * @return Response
     */
    public static function success(string $msg): Response
    {
        return self::json($msg, 200);
    }

    /**
     * 返回成功消息带数据
     *
     * @param string $msg
     * @param array $data
     * @return Response
     */
    public static function successFul(string $msg, array $data): Response
    {
        return self::json($msg, 200, $data);
    }

    /**
     * 返回成功结果
     *
     * @param array $data
     * @return Response
     */
    public static function successRes(array $data): Response
    {
        return self::json('success', 200, $data);
    }

    /**
     * 返回失败消息
     *
     * @param string $msg
     * @return Response
     */
    public static function fail(string $msg): Response
    {
        return self::json($msg, 404);
    }

    /**
     * 返回失败待状态码消息
     *
     * @param string $msg
     * @param integer $code
     * @return Response
     */
    public static function failFul(string $msg, int $code): Response
    {
        return self::json($msg, $code);
    }
}
