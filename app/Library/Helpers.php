<?php
/**
 * 用户自定义全局方法
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午2:58
 */

if(!function_exists('siteEnv')) {
    /**
     * 判断当前环境是否为生产环境
     * @return bool
     */
    function siteEnv() {
        return $_SERVER['SITE_ENV'] ?? 'testing';
    }
}

if(!function_exists('isProdEnv')) {
    /**
     * 判断当前环境是否为生产环境
     * @return bool
     */
    function isProdEnv() {
        return siteEnv() == 'production';
    }
}

if(!function_exists('substr_str')) {
    /**
     * 将字符串按照指定字符第一次出现的位置截取
     * @param string $string 字符串
     * @param string $start  从这个字符截取
     * @param string $end    在这个字符结束
     * @return bool|string
     */
    function substr_str($string, $start, $end) {
        $from = !empty($start) ? strpos($string, $start) : false;
        $from = $from === false ? 0 : $from + 1;
        $to = !empty($end) ? strpos($string, $end) : false;
        $to = $to === false ? strlen($string) : $to;
        return substr_pos($string, $from, $to);
    }
}

if(!function_exists('subrstr_str')) {
    /**
     * 将字符串按照指定字符最后一次出现的位置截取
     * @param string $string 字符串
     * @param string $start  从这个字符截取
     * @param string $end    在这个字符结束
     * @return bool|string
     */
    function subrstr_str($string, $start, $end) {
        $from = !empty($start) ? strrpos($string, $start) : false;
        $from = $from === false ? 0 : $from + 1;
        $to = !empty($end) ? strrpos($string, $end) : false;
        $to = $to === false ? strlen($string) : $to;
        return substr_pos($string, $from, $to);
    }
}

if(!function_exists('substr_pos')) {
    /**
     * 将字符串从指定位置截取
     * @param string $string 字符串
     * @param int    $from   从这截取
     * @param int    $to     在这结束
     * @return bool|string
     */
    function substr_pos($string, $from, $to) {
        return substr($string, $from, $to - $from);
    }
}

if(!function_exists('array_add_item')) {
    /**
     * 向数组中添加不存在的值
     * @param array $array
     * @param int|string $value
     */
    function array_add_item(&$array, $value) {
        in_array($value, $array) || $array[] = $value;
    }
}

if(!function_exists('array_contain')) {
    /**
     * 数组是否包含键
     * @param array        $array
     * @param array|string $keys
     * @return bool
     */
    function array_contain($array, $keys = []) {
        if(empty($keys)) {
            return false;
        }
        is_array($keys) || $keys = [$keys];
        $flag = true;
        foreach($keys as $key) {
            if(!isset($array[$key])) {
                $flag = false;
                break;
            }
        }
        return $flag;
    }
}

if(!function_exists('array_compare')) {
    /**
     * 比较两数组
     * @param array $before
     * @param array $after
     * @return  array $result [$after比$before增加的元素, 减少的元素]
     */
    function array_compare($before, $after) {
        $result = [[], []];
        foreach($after as $value) {
            !in_array($value, $before) && array_add_item($result[0], $value);
        }
        foreach($before as $value) {
            !in_array($value, $after) && array_add_item($result[1], $value);
        }
        return $result;
    }
}

if(!function_exists('msleep')) {
    /**
     * 毫秒记sleep
     * @param int $ms 毫秒数
     */
    function msleep($ms) {
        usleep($ms * 1000);
    }
}

if(!function_exists('millitime')) {
    /**
     * 获取当前毫秒数
     * @return int
     */
    function millitime() {
        return floor(microtime(true) * 1000);
    }
}

if(!function_exists('datetimeNow')) {
    /**
     * 获取当前格式化时间
     * @return string
     */
    function datetimeNow() {
        return date('Y-m-d H:i:s');
    }
}

if(!function_exists('spend')) {
    /**
     * 获取项目执行时间
     * 单位ms
     * @return int
     */
    function spend() {
        $start = floor(LARAVEL_START * 1000);
        $end = millitime();
        return intval($end - $start);
    }
}

/**
 * 数字转换类型常量
 */
!defined('DECIMAL2PERCENT') && define('DECIMAL2PERCENT', 1); // 小数转百分数
!defined('PERCENT2DECIMAL') && define('PERCENT2DECIMAL', 2); // 百分数转小数
!defined('YUAN2FEN') && define('YUAN2FEN', 3); // 元转分
!defined('FEN2YUAN') && define('FEN2YUAN', 4); // 分转元
!defined('MONTH2YEAR') && define('MONTH2YEAR', 5); // 月转年
!defined('YEAR2MONTH') && define('YEAR2MONTH', 6); // 年转月
if(!function_exists('numberConvert')) {
    /**
     * 常用数字转换
     * @param int|float $num 转换前数字
     * @param int $type      转换类型
     * @param string $suffix 后缀, 如'%', '元'等
     * @return string $num  转换后数字
     */
    function numberConvert($num, $type = DECIMAL2PERCENT, $suffix = '') {
        switch($type) {
            case DECIMAL2PERCENT: {
                $num *= 100;
                break;
            }
            case PERCENT2DECIMAL: {
                $num /= 100;
                break;
            }
            case YUAN2FEN: {
                $num = intval(round($num * 100));
                break;
            }
            case FEN2YUAN: {
                $num = round($num / 100, 2);
                break;
            }
            case MONTH2YEAR: {
                $num = intval(round($num / 12));
                break;
            }
            case YEAR2MONTH: {
                $num *= 12;
                break;
            }
            default: {
                $num = 0;
                break;
            }
        }
        $num .= $suffix;
        return $num;
    }
}