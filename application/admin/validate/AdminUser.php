<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017/12/31 14:52
// +----------------------------------------------------------------------

namespace app\admin\validate;

use \app\admin\model\AdminUser as AdminUserModel;

class AdminUser extends BaseValidate
{
    protected $rule = [
        'account'   => 'require',
        'password'  => 'require|checkUser',
    ];

    protected $message = [
        'account.require'    => '请输入账号',
        'password.require'   => '请输入密码',
    ];

    protected $scene = [
        'updatePassword' => [
            'password' => 'require|checkPassword'
        ],
    ];

    protected function checkUser($value, $rule, $data)
    {
        $userInfo = AdminUserModel::get(['account' => $data['account']]);
        if(empty($userInfo)) {
            return '此用户不存在';
        }
        $password = md5(md5($value).$userInfo->salt_value);
        if ($userInfo->password !== $password) {
            return '密码错误';
        }
        if($userInfo->status !== 1) {
            return '账号已冻结';
        }
        return true;
    }

    protected function checkPassword($value, $rule, $data)
    {
        $userInfo = AdminUserModel::get(function ($query) {
            $query->where('account', session('user_info.account'))->field('password, salt_value');
        });
        $orgPassword = md5(md5($data['org_password']).$userInfo->salt_value);
        if ($userInfo->password !== $orgPassword) {
            return '原密码错误';
        }
        if (strlen($value) < 6) {
            return '密码长度不能小于6';
        }
        if ($value !== $data['confirm']) {
            return '两次输入的密码不一致';
        }
        $newPassword = md5(md5($value).$userInfo->salt_value);
        if ($userInfo->password == $newPassword) {
            return '新密码与原密码一致';
        }
        $userInfo->password = $newPassword;
        $result = $userInfo->save();
        if (!$result) {
            return '内部错误';
        }
        return true;
    }
}