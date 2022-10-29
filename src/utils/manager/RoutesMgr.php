<?php

namespace Hangpu8\Admin\utils\manager;

use Hangpu8\Admin\middleware\AccessMiddleware;
use Hangpu8\Admin\model\SystemAuthRule;
use Webman\Route;
use support\Request;

/**
 * @title 路由管理器
 * @desc 仅用于HPAdmin后端路由注册相关业务
 * @author 楚羽幽 <admin@hangpu.net>
 */
class RoutesMgr
{
    /**
     * 初始化路由
     *
     * @return void
     */
    public static function init()
    {
        // 安装完成
        Route::add(['GET', 'POST'], '/install/step5', '\Hangpu8\Admin\install\InstallController@step5')
            ->middleware([
                AccessMiddleware::class
            ]);
        // 检测是未安装（注册路由）
        if (!file_exists(base_path() . '/.env')) {
            RoutesMgr::install();
        } else {
            RoutesMgr::installed();
        }
    }

    /**
     * 注册未安装路由
     *
     * @return void
     */
    private static function install()
    {
        // 跳转安装
        Route::add(['GET'], '/', '\Hangpu8\Admin\install\InstallController@index');
        // 安装业务流程
        Route::group('/install', function () {
            // 安装首页
            Route::add(['GET'], '/step1', '\Hangpu8\Admin\install\InstallController@step1');
            // 检测环境
            Route::add(['GET'], '/step2', '\Hangpu8\Admin\install\InstallController@step2');
            // 数据库设置
            Route::add(['GET', 'POST'], '/step3', '\Hangpu8\Admin\install\InstallController@step3');
            // 开始安装
            Route::add(['POST'], '/step4', '\Hangpu8\Admin\install\InstallController@step4');
        })->middleware([
            AccessMiddleware::class
        ]);
        // 加载视图
        $installPath = str_replace('\\', '/', dirname(__DIR__)) . '/install';
        Route::any('/install/view/', function (Request $request, $path = '') use ($installPath) {
            // 安全检查，避免url里 /../../../password 这样的非法访问
            if (strpos($path, '..') !== false) {
                return response('<h1>400 Bad Request</h1>', 400);
            }
            $file = "{$installPath}/view/index.html";
            if (!is_file($file)) {
                return response('<h1>404 Not Found</h1>', 404);
            }
            return response()->file($file);
        });
        // 静态资源
        Route::any('/hpadmin/[{path:.+}]', function (Request $request, $path = '') use ($installPath) {
            // 安全检查，避免url里 /../../../password 这样的非法访问
            if (strpos($path, '..') !== false) {
                return response('<h1>400 Bad Request</h1>', 400);
            }
            // 文件
            $file = "{$installPath}/view/hpadmin/{$path}";
            if (!is_file($file)) {
                return response('<h1>404 Not Found</h1>', 404);
            }
            return response()->withFile($file);
        });
    }

    /**
     * 注册已安装路由
     *
     * @return void
     */
    private static function installed()
    {
        $where[] = ['auth_type', '=', 2];
        $routes = SystemAuthRule::where($where)->select()->toArray();

        // 注册组路由
        Route::group('/', function () use ($routes) {
            foreach ($routes as $route) {
                $methods = explode(',', $route['method']);
                list($controller, $action) = explode('/', $route['path']);
                $controller = ucfirst($controller);
                Route::add($methods, "{$controller}/{$action}", "{$route['namespace']}{$controller}Controller@{$action}");
            }
        })->middleware([
            AccessMiddleware::class,
        ]);
    }
}
