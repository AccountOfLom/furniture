<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2018/2/21 
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model as CoreModel;

abstract class Model extends CoreModel
{
    protected $autoWriteTimestamp = true;
    protected $autoSave = true;
    //save方法原始数据
    protected $saveData = [];
    protected $deleteTime = null;

    /**
     * 格式化时间戳输出
     * @param $value
     * @return false|string
     */
    protected function getLoginLastTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 判断是否带有主键数据
     * @param $data
     * @return bool
     * @throws \think\exception\DbException
     */
    private function hasPk($data)
    {
        $find = $this->get($this->checkPkData($data));
        return empty($find) ? false : true;
    }

    /**
     * 得到主键数据
     * @param $data
     * @return array|int
     */
    private function checkPkData($data)
    {
        $pk = $this->getPk();
        if(is_array($pk) && count(array_intersect_key($data, array_flip($pk))) === count($pk))
        {
            return array_intersect_key($data, array_flip($pk));
        }
        elseif (is_string($pk))
        {
            return isset($data[$pk]) ? $data[$pk] : 0;
        }
        else
        {
            \think\Log::error($this->getTable().'未找到主键数据:'.json_encode($data));
            throw new \think\exception\HttpException(500, '请求数据异常');
        }
    }

    /**
     * 是否自动判断数据库新增或修改
     * @param bool $status
     * @return $this
     */
    public function autoSave($status = true)
    {
        $this->autoSave = $status;
        return $this;
    }


    /**
     * 保存当前数据对象 字段过滤
     * @param array $data       数据
     * @param array $where      更新条件
     * @param null $sequence    自增序列名
     * @return false|int
     * @throws \think\exception\DbException
     */
    public function save($data = [], $where = [], $sequence = null)
    {
        $data = $data ?: $this->getData();
        $this->saveData = $data;
        $this->allowField(true);
        if($this->autoSave)
        {
            $this->isUpdate = $this->hasPk($data);
        }
        return parent::save($data,$where,$sequence);
    }
}