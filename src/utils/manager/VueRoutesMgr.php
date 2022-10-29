<?php

namespace Hangpu8\Admin\utils\manager;

use Hangpu8\Admin\model\SystemAuthRule;

/**
 * @title 路由管理器
 * @desc 仅用于HPAdmin后端路由注册相关业务
 * @author 楚羽幽 <admin@hangpu.net>
 */
class VueRoutesMgr
{
    /**
     * 获取Vue完整路由
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        $active = 'Index/index';
        $where = [
            ['show', '=', 1],
            ['auth_type', '<>', 2],
        ];
        $data = SystemAuthRule::where($where)->select()->toArray();
        $routes = DataMgr::channelLevel($data, '', '', 'path');
        $routes = self::analysis($routes, $active);

        return $routes;
    }

    /**
     * 解析为Vue前端路由格式
     *
     * @param array $data
     * @return array
     */
    private static function analysis(array $routes, string $active): array
    {
        $data = [];
        foreach ($routes as $value) {
            $name = ucfirst(str_replace('/', '_', $value['path']));
            $path = ucfirst($value['path']);
            $item = [
                'title'         => $value['title'],
                'icon'          => $value['icon'],
                'path'          => "/{$path}",
                'name'          => $name,
                'component'     => $value['auth_rule'],
                'auth_params'   => $value['auth_params'],
                'children'      => []
            ];
            if ($value['path'] == 'Index/tabs') {
                $item['redirect'] = $active;
            }
            if ($value['_data'] && !empty($value['_data'])) {
                $item['children'] = self::analysis($value['_data'], $active);
            }
            array_push($data, $item);
        }
        return $data;
    }
}
