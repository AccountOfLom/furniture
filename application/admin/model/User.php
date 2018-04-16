<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017/12/30 16:25
// +----------------------------------------------------------------------

namespace app\admin\model;


class User extends BaseModel
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
     * 获取一个用户信息
     * @param array $map
     * @param bool $field
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getOne($map = [], $field = true)
    {
        return self::where($map)->field($field)->find();
    }


    /**
     * 保存方法
     * @param $saveData
     * @return mixed;
     */
    public function doSave($saveData)
    {
        $saveData['image'] = empty($saveData['org_image']) ? DEFAULT_IMAGE_PATH : $saveData['org_image'];
        if ($saveData['upload_image']) {
            $imgPath = self::uploadImg('image', 'user_icon');
            $saveData['image'] = $imgPath ?? $saveData['image'];
        }
        if (empty($saveData['id'])) {
            $saveData['salt_value'] = create_salt_value();
            $saveData['password'] = md5(md5($saveData['password']) . $saveData['salt_value']);
        }
        return self::save($saveData);
    }
}