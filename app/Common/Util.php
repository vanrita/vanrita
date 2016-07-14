<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Util.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/03/23 12:11:27
 * @brief 
 *  
 **/

namespace App\Common;


class Util {

    /**
     * Laravel的类类型转换string
     * @param $std
     * @return array
     */
    public static function stdToArray($std)
    {
        if (is_object($std)) {
            $std = (array)$std;
        }
        if (is_array($std)) {
            foreach ($std as $varName => $varValue) {
                $std[$varName] = self::stdToArray($varValue);
            }
        }
        return $std;
    }

    /**
     * @param $value1
     * @param $value2
     * @return float
     */
    public static function calRadio($value1, $value2) {
        return round(($value1 - $value2) / $value1, 2);
    }

}

?>
