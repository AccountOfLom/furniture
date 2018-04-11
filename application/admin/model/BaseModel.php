<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017/12/30 15:35
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;
}