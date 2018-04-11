<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017/12/30 16:25
// +----------------------------------------------------------------------

namespace app\admin\model;


class AdminUser extends BaseModel
{
    /**
     * 设置用户 session
     * @param $value
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function setUserSession($value)
    {
        $userInfo = self::get(['id|account' => $value]);
        unset($userInfo->password);
        if (!$userInfo) {
            return false;
        }
        session('user_info', $userInfo);

        $this->autoWriteTimestamp = false;  //不做更新时间记录

        //60s 时间间隔的登录操作被记录，并更新最后登录时间
        if ($userInfo->setInc('login_count', 1, 60) === 1) {
            $userInfo->login_last_time = time();
            $userInfo->save();
        }
        return true;
    }

    /**
     * 根据字段条件查询
     * @param array $where              条件
     * @param boolean|string $showFields    读取字段值
     * @throws \think\exception\DbException
     * @return false|static[]
     * @throws \think\exception\DbException
     */
    public function getInfo($where = [], $showFields = true)
    {
        return self::all(function ($query) use ($where, $showFields) {
            $query->where($where)->field($showFields);
        });
    }

}