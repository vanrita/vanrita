<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file ShopIncDec.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/07/04 16:14:53
 * @brief 
 *  
 **/

namespace App\Models;

use App\Common\Util;
use DB;

class ShopIncDec
{

    protected $objDao;
    protected static $objInstance;

    /**
     * ShopInfo constructor.
     */
    public function __construct()
    {
        $this->objDao = DB::connection();
    }

    /**
     * 单例模式
     * @var ShopIncDec
     * @return ShopIncDec 单例对象
     */
    public static function getInstance()
    {
        if (!self::$objInstance) {
            self::$objInstance = new self();
        }
        return self::$objInstance;
    }


    /**
     * 按时间获取当前所有门店数
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getAllByTime($start_time, $end_time)
    {
        $query = 'select sum(shop_cnt) as shop, sum(inc_cnt) as inc, sum(dec_cnt) as reduce, sum(stay_cnt) as stay
                  from shop_inc_dec where time >= ? and time <= ?;';
        $ret = $this->objDao->select($query, [$start_time, $end_time]);
        if ($ret == null) {
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret;
    }

    /**
     * 按时间获取各渠道门店数
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getAllSourceByTime($start_time, $end_time)
    {
        $query = 'select create_source_code, shop_cnt as shop, inc_cnt as inc, dec_cnt as reduce, stay_cnt as stay, DATE_FORMAT(time,"%Y%m%d") day
                  from shop_inc_dec where time >= ? and time <= ? group by day, create_source_code';
        $ret = $this->objDao->select($query, [$start_time, $end_time]);
        if ($ret == null) {
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret;
    }
}

?>
