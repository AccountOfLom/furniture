<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2018/2/21 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use app\admin\model\User as userModel;

class User extends BaseController
{
    //头像（正方形）最大边长
    const IMAGE_MAX_LENGTH = 300;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->currentModel = new userModel;
    }

    public function index()
    {
        return parent::index();
    }

    public function data()
    {
        $key = input('key');
        $value = trim(input('value'));
        $value = empty($value) ? '%' : '%' . $value . '%';
        return $this->currentModel->whereLike($key, $value)->dataTable([], ['password']);
    }

    public function edit()
    {
        if ($this->request->has('id', 'param', true)) {
            $userInfo = $this->currentModel->getOne(['id' => input('id')], 'id,account,user_name,image,role_id,status');
            if ($userInfo)
                $this->assign('userInfo', $userInfo);
        }
        $this->assign('imgLength', self::IMAGE_MAX_LENGTH);

        //角色
        $roles = model('role')->field('id, role_name')->select();
        $this->assign('roles', $roles);
        return $this->fetch();
    }

    public function save()
    {
        if (empty($this->params)) {
            $this->error('请填写数用户信息');
        }

        $this->params['upload_image'] = $this->request->file('image');
        $this->params['imageMaxLength'] = self::IMAGE_MAX_LENGTH;

        $validateResult = $this->validate($this->params, 'User.edit');
        if ($validateResult !== true) {
            $this->error($validateResult);
        }

        $row = $this->currentModel->doSave($this->params);
        if ($row) {
            $this->success('保存成功', 'user/index');
        }
        $this->error('保存失败');

    }

    /**
     * 密码修改页
     * @return mixed
     */
    public function password()
    {
        return $this->fetch();
    }

    /**
     * 修改密码
     */
    public function updatePassword()
    {
        $params = $this->request->param();
        $checkResult = $this->validate($params, 'User.updatePassword');
        if(true !== $checkResult){
            $this->error($checkResult);
        }
        $this->success('修改成功');
    }

    /**
     * 远程校验
     * @param $id
     * @param $account
     * @return string
     */
    public function account_exist($id, $account)
    {
        if (empty($account)) {
            $row = $this->currentModel->getOne(['account' => $account]);
        } else {
            $row = $this->currentModel->getOne(['id' => ['neq', $id], 'account' => $account]);
        }
        if ($row) {
            return '此账号名已存在';
        }
        return true;
    }

    public function user_name_exist($id, $user_name)
    {
        if (empty($user_name)) {
            $row = $this->currentModel->getOne(['account' => $user_name]);
        } else {
            $row = $this->currentModel->getOne(['id' => ['neq', $id], 'account' => $user_name]);
        }
        if ($row) {
            return '此昵称已存在';
        }
        return true;
    }
}