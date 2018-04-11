<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017-12-20 23:25
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Controller;

class BaseController extends Controller
{
    //当前模型
    protected $currentModel;

    protected function _initialize()
    {
        //登录状态判断
        if (!session('user_info')) {
            $this->redirect('Login/index');
        }
        $menu = model('Menu');
        //得到当前操作地址
        $fullUrl = $this->request->module().'/'.$this->request->controller().'/'.$this->request->action();
        //得到当前控制器信息
        $params = $this->request->get();
        if($params)
        {
            $currentMenu = $menu->where(['controller'=>$fullUrl])->whereExp('','LOCATE("'.http_build_query($params).'",params) > 0')->value('id');
            $pidMenu = $menu->where(['controller'=>$fullUrl])->whereExp('','LOCATE("'.http_build_query($params).'",params) > 0')->value('pid');
        }

        $currentMenu = isset($currentMenu) && !empty($currentMenu) ? $currentMenu : $menu->where(['controller'=>$fullUrl])->value('id');
        $pidMenu = isset($pidMenu) && !empty($pidMenu) ? $pidMenu : $menu->where(['controller'=>$fullUrl])->value('pid');

        $this->assign('currentMenu',$currentMenu);
        //得到当前父控制器信息
        $this->assign('pidMenu',$pidMenu);
        $auth = db('role')->whereIn('id',user_info('role_id'))->column('role_auth');
        $role = [];
        foreach ($auth as $item)
        {
            $role = array_merge($role,explode(',',$item));
        }
        $this->assign('auth',array_unique($role));
        if(!$this->checkAuth($currentMenu,array_unique($role)))
        {
            $this->error('无操作权限');
        }
        //得到权限菜单列表
        $menuList = $menu->where('visible',1)->field('params',true)->field('params as params_array')->order('sort')->select();
        $menuList = \Tree::getTree($menuList);
        $this->assign('menus',$menuList);
        //得到权限菜单列表
        $allMenu = $menu->order('sort')->field('params',true)->field('params as params_array')->select();
        $allMenu = \Tree::getTree($allMenu);
        $this->assign('allMenu',$allMenu);
    }

    private function checkAuth($currentMenu,$auth)
    {
        $menu = model('Menu');
        $allMenu = $menu->column('id');
        if(user_info('account') == 'admin') return true;
        if(in_array($currentMenu,$allMenu))
        {
            if(in_array($currentMenu,$auth))
            {
                return true;
            }
            return false;
        }
        return true;
    }

}
