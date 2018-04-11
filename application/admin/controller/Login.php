<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017/12/20 23:29
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\AdminUser;
use think\Controller;
use think\Loader;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        if (session('user_info')) {
            $this->redirect('Index/index');
        } else {
            return $this->fetch();
        }
    }

    public function login()
    {
        $validate = Loader::validate('AdminUser');
        if(!$validate->check($this->request->param())){
            $result = [
                'status'  => false,
                'message' =>  $validate->getError()
            ];
        } else {
            (new AdminUser())->setUserSession($this->request->param('account'));
            $backUrl = redirect()->restore()->getData();
            $result = [
                'status' => true,
                'data' => ['url' => $backUrl ? $backUrl : 'Index/index']
            ];
        }
        return $result;
    }

    public function loginOut()
    {
        Session::clear();
        $this->redirect('Login/index');
    }
}