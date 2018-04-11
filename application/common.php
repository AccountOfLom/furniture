<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if(!function_exists('array_mulit_exists'))
{
    /**
     * @param $array 待搜索多维数组
     * @param $value 查找的值
     * @param string $find_key 查找的键(为空代表搜索所有键)
     * @param bool $find 是否已经找到
     * @return bool
     */
    function array_mulit_exists($array,$value,$find_key='',&$find = false)
    {
        if(!$find && is_array($array))
        {
            foreach ($array as $key => $item)
            {
                if($find)
                {
                    break;
                }
                if(is_array($item))
                {
                    array_mulit_exists($item,$value,$find_key,$find);
                }
                else
                {
                    if(empty($find_key) && $value === $item)
                    {
                        $find = true;
                        break;
                    }
                    elseif($value === $item && $key === $find_key )
                    {
                        $find = true;
                        break;
                    }
                }
            }
        }
        return $find;
    }
}
