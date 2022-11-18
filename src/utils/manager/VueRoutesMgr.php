<?php

namespace Hangpu8\Admin\utils\manager;

use Exception;
use Hangpu8\Admin\model\SystemAdminRole;
use Hangpu8\Admin\model\SystemAuthRule;

/**
 * @title 路由管理器
 * @desc 仅用于HPAdmin后端路由注册相关业务
 * @author 楚羽幽 <admin@hangpu.net>
 */
class VueRoutesMgr
{
    // 设置允许输出字段
    private static $visible = [
        'id',
        'module',
        'namespace',
        'pid',
        'path',
        'title',
        'method',
        'auth_rule',
        'auth_params',
        'icon',
        'show',
    ];

    /**
     * 获取管理员权限
     *
     * @param integer $role_id
     * @return array
     */
    public static function getRoutes(int $role_id): array
    {
        // 默认选择菜单
        $active = 'hpadmin/Index/index';
        // 获取管理员权限
        $roleRule = self::getAdminRoleRule($role_id);
        $data = DataMgr::channelLevel($roleRule, '', '', 'path');
        // 二次处理规则
        $data = self::checkRules($data);
        // 返回前端Vue格式数据
        $routes = self::analysis($data, $active);
        // 返回数据
        return $routes;
    }

    /**
     * 二次处理权限规则
     *
     * @param array $data
     * @return array
     */
    private static function checkRules(array $data): array
    {
        foreach ($data as $key => $value) {
            // 隐藏菜单移除
            if ($value['show'] == 0) {
                unset($data[$key]);
            }
            // 子菜单检测
            if ($value['children']) {
                $data[$key]['children'] = self::checkRules($value['children']);
            }
        }
        return $data;
    }

    /**
     * 获取部门权限
     *
     * @param integer $role_id
     * @return array
     */
    public static function getAdminRoleRule(int $role_id): array
    {
        $where = [
            ['id', '=', $role_id],
        ];
        $roleModel = SystemAdminRole::where($where)->field('rule,is_system')->find();
        if (!$roleModel) {
            throw new Exception('该部门不存在');
        }
        if ($roleModel->is_system == 1) {
            // 系统级部门（全部权限）
            $where = [];
            $data = SystemAuthRule::where($where)
                ->order('sort', 'asc')
                ->visible(self::$visible)
                ->select()
                ->toArray();
        } else {
            // 普通级部门（按授权规则）
            $rule = explode(',', $roleModel->rule);
            $where = [
                ['path', 'in', $rule],
            ];
            $data = SystemAuthRule::where($where)
                ->order('id', 'asc')
                ->visible(self::$visible)
                ->select()
                ->toArray();
            foreach ($data as $value) {
                if (!in_array($value['pid'], $rule) && $value['pid']) {
                    // 递归查找父级权限
                    self::getParentRule($data, $value['pid']);
                }
            }
            $data = list_sort_by($data, 'sort', 'asc');
        }
        return $data;
    }

    /**
     * 获取部门权限规则列表
     *
     * @param integer $role_id
     * @return array
     */
    public static function getAdminRoleColumn(int $role_id): array
    {
        $rule = self::getAdminRoleRule($role_id);
        foreach ($rule as $key => $value) {
            $data[$key] = $value['path'];
        }
        return $data;
    }

    /**
     * 递归查询父级规则
     *
     * @param string $rule
     * @return array
     */
    private static function getParentRule(array &$list, string $rule): array
    {
        $where = [
            ['path', '=', $rule],
        ];
        $model = SystemAuthRule::where($where)
            ->visible(self::$visible)
            ->find();
        if (!$model) {
            throw new Exception('父级规则不存在');
        }
        $data = $model->toArray();
        array_push($list, $data);
        if ($data['pid']) {
            self::getParentRule($list, $data['pid']);
        }
        return $list;
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
                'path'          => "{$value['module']}/{$path}",
                'method'        => $value['method'],
                'auth_params'   => $value['auth_params'],
                'name'          => $name,
                'component'     => $value['auth_rule'],
                'children'      => []
            ];
            if ($value['path'] == 'Index/tabs') {
                $item['redirect'] = $active;
            }
            if ($value['children'] && !empty($value['children'])) {
                $item['children'] = self::analysis($value['children'], $active);
            }
            array_push($data, $item);
        }
        return $data;
    }
}
