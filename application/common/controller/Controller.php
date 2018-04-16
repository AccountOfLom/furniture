<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2018/2/21 
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller as CoreController;
use think\Request;

abstract class Controller extends CoreController
{
    public $params = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        if (!empty($this->request->param()))
            $this->params = $this->request->param();
    }



}