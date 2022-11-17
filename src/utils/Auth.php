<?php

namespace Hangpu8\Admin\utils;

use Hangpu8\Admin\utils\manager\VueRoutesMgr;

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
     * @param string $control
     * @param string $action
     * @param string $msg
     * @param integer $code
     * @return boolean
     */
    public static function canAccess(string $control, string $action, string &$msg, int &$code): bool
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
        $admin = hp_admin();
        if (!$admin) {
            // 401是未登录固定的返回码
            $code = 401;
            $msg = '请先登录';
            return false;
        }

        // 不需要鉴权
        if (in_array($action, $noNeedAuth)) {
            return true;
        }
        // 系统级部门，不需要鉴权
        if ($admin['is_system'] == 1) {
            return true;
        }
        // 获取角色规则
        $rule = VueRoutesMgr::getAdminRoleColumn((int) $admin['role_id']);
        // 检测是否有操作权限
        $ctrlName = str_replace('Controller', '', basename(str_replace('\\', '/', $control)));
        $path = "{$ctrlName}/{$action}";
        if (!in_array($path, $rule)) {
            $code = 403;
            $msg = '没有该操作权限';
            return false;
        }
        return true;
    }
}
