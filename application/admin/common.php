<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2017/12/30 18:30
// +----------------------------------------------------------------------

if (!function_exists('user_info')) {
    function user_info($key) {
        return session('user_info.' . $key);
    }
}