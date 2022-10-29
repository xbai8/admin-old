<?php

namespace Hangpu8\Admin\controller;

use Exception;
use support\Request;
use Hangpu8\Admin\Base;
use Hangpu8\Admin\model\SystemAdmin;
use Hangpu8\Admin\validate\SystemAdmin as ValidateSystemAdmin;

class PublicsController extends Base
{
    /**
     * 不需要登录的方法
     * @var string[]
     */
    protected $noNeedLogin = ['site', 'login', 'logout', 'captcha'];

    /**
     * 应用信息
     *
     * @return void
     */
    public function site()
    {
    }

    /**
     * 系统登录
     *
     * @return void
     */
    public function login(Request $request)
    {
        // 获取数据
        $post = $request->post();
        // 数据验证
        hpValidate(new ValidateSystemAdmin, $post, 'login');

        // 查询数据
        $where['admin.username'] = $post['username'];
        $field = [
            'admin.*',
            'role.title as level'
        ];
        $adminModel = SystemAdmin::alias('admin')
            ->join('system_admin_role role', 'role.id=admin.role_id')
            ->where($where)
            ->field($field)
            ->find();
        if (!$adminModel) {
            throw new Exception('登录账号错误');
        }
        $admin = $adminModel->toArray();
        unset($admin['password']);
        $session = $request->session();
        $session->set('admin', $admin);

        // 更新登录信息
        $ip = $request->getRealIp($safe_mode = true);
        $adminModel->last_login_ip = $ip;
        $adminModel->last_login_time = date('Y-m-d H:i:s');
        $adminModel->save();

        // 返回数据
        return parent::successFul('登录成功', ['token' => $request->sessionId()]);
    }
}
