<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 7/9/20
 * Time: 9:44 AM
 */

namespace App\Classes;

/**
 * 查询空字段过滤
 * Class QueryFilter
 * @package App\Classes
 */
class QueryFilter
{
    public static function get(array $src, array $target)
    {
        return array_filter($src, function ($value, $key) use ($target){
            if (!in_array($key, $target)) return false;
            return is_array($value) ? count($value) > 0 : ($value !== '' && $value !== null);
        }, ARRAY_FILTER_USE_BOTH);
    }
}