<?php

namespace Hangpu8\Admin\middleware;

use Hangpu8\Admin\utils\Auth;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * @title 权限检测中间件
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class AccessMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // 从headers中拿去请求用户token
        $authorization = $request->header('Authorization');
        $request->sessionId($authorization);
        // 获得请求路径
        $controller = $request->controller;
        $action = $request->action;
        $msg = '';
        $code = 0;
        // 鉴权检测
        if (!Auth::canAccess($controller, $action, $msg, $code)) {
            $response = json(['code' => $code, 'msg' => $msg]);
        } else {
            $response = $request->method() == 'OPTIONS' ? response('', 204) : $handler($request);
        }
        return $response;
    }
}
