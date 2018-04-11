<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2018/2/21 
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\AdminUser as AdminUserModel;
use think\Request;

class AdminUser extends BaseController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->currentModel = new AdminUserModel();
    }

    public function index()
    {
        return $this->fetch();
    }

    public function data()
    {
        $key = input('key');
        $value = trim(input('value'));
        $value = empty($value) ? '%' : '%'.$value.'%';
        return $this->currentModel->whereLike($key, $value)->dataTable([], ['password']);
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
        $checkResult = $this->validate($params, 'AdminUser.updatePassword');
        if(true !== $checkResult){
            $this->error($checkResult);
        }
        $this->success('修改成功');
    }
}