<?php

namespace Hangpu8\Admin\utils;

use Exception;
use Hangpu8\Admin\model\SystemAdminRole;

/**
 * @title 权限管理
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Auth
{
    /**
     * 检测是否有权限
     *
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public static function canAccess(string $control, string $action): bool
    {
        // 无控制器地址
        if (!$control) {
            return true;
        }
        // 获取控制器鉴权信息
        $class = new \ReflectionClass($control);
        $properties = $class->getDefaultProperties();
        $noNeedLogin = $properties['noNeedLogin'] ?? [];
        $noNeedAuth = $properties['noNeedAuth'] ?? [];

        // 不需要登录
        if (in_array($action, $noNeedLogin)) {
            return true;
        }
        // 获取登录信息
        $admin = admin();
        if (!$admin) {
            // 401是未登录固定的返回码
            throw new Exception('请先登录', 401);
        }

        // 不需要鉴权
        if (in_array($action, $noNeedAuth)) {
            return true;
        }
        // 获取角色规则
        $where = [
            'id'        => $admin['role_id']
        ];
        $adminRoleModel = SystemAdminRole::where($where)->field('rule')->find();
        if (!$adminRoleModel) {
            throw new Exception('该部门不存在', 401);
        }
        // 检测是否有操作权限
        // $rule = explode(',', $adminRoleModel->rule);
        // $ctrlName = str_replace('Controller', '', basename(str_replace('\\', '/', $control)));
        // $path = "{$ctrlName}/{$action}";
        // if (!in_array($path, $rule)) {
        //     throw new Exception('没有该操作权限', 401);
        // }
        return true;
    }
}
