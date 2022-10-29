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
        $controller = $request->controller;
        $action = $request->action;
        Auth::canAccess($controller, $action);

        $response = $request->method() == 'OPTIONS' ? response('', 204) : $handler($request);
        return $response->withHeaders([
            'Access-Control-Allow-Credentials'  => 'true',
            'Access-Control-Allow-Origin'       => $request->header('Origin', '*'),
            'Access-Control-Allow-Methods'      => 'GET,POST,PUT,DELETE,HEAD',
            'Access-Control-Allow-Headers'      => 'Authorization, Origin, X-Requested-With, X-PJAX, Content-Type, Accept',
        ]);
    }
}
