<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file QualityFunnel.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/06/30 18:26:01
 * @brief 
 *  
 **/


namespace App\Models;

use App\Common\Util;
use DB;

class QualityFunnel
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
     * @var QualityFunnel
     * @return QualityFunnel 单例对象
     */
    public static function getInstance()
    {
        if (!self::$objInstance) {
            self::$objInstance = new self();
        }
        return self::$objInstance;
    }

    /**
     * 查询全局指标店铺数
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getAllByTime($start_time, $end_time) {
        $query = 'select static_index, DATE_FORMAT(time,"%Y%m%d") day, sum(shop_cnt) as sum from quality_funnel
                  where time >= ? and time <= ? group by day, static_index;';
        $ret = $this->objDao->select($query, [$start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret;
    }

    /**
     * 查询来源指标店铺数
     * @param $source
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getAllByTimeAndSource($source, $start_time, $end_time) {
        $query = 'select static_index, DATE_FORMAT(time,"%Y%m%d") day, sum(shop_cnt) as sum from quality_funnel
                  where create_source_code = ? and time >= ? and time <= ? group by day, static_index;';
        $ret = $this->objDao->select($query, [$source, $start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret;
    }

    /**
     * 通过纬度查询店铺数
     * @param $static_index
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getAllByTimeAndIndex($static_index, $start_time, $end_time) {
        $query = 'select sum(shop_cnt) as sum from quality_funnel where static_index = ? and time >= ? and time <= ?;';
        $ret = $this->objDao->select($query, [$static_index, $start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret[0];
    }

    /**
     * 通过纬度,模板类型查询店铺数
     * @param $static_index
     * @param $template_id
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getCountsByIndexAndTemplate($static_index, $template_id, $start_time, $end_time) {
        $query = 'select sum(shop_cnt) as sum from shop_info where static_index = ? and template_id = ? and time >= ? and time <= ?;';
        $ret = $this->objDao->select($query, [$static_index, $template_id, $start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret[0];
    }

    /**
     * 通过纬度,行业类型查询店铺数
     * @param $static_index
     * @param $trade2
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getCountsByIndexAndTrade($static_index, $trade2, $start_time, $end_time) {
        $query = 'select sum(shop_cnt) as sum from shop_info where static_index = ? and trade2 = ? and time >= ? and time <= ?;';
        $ret = $this->objDao->select($query, [$static_index, $trade2, $start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret[0];
    }

    /**
     * 通过纬度,来源查询店铺数
     * @param $static_index
     * @param $source
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getCountsByIndexAndSource($static_index, $source, $start_time, $end_time) {
        $query = 'select sum(shop_cnt) as sum from shop_info where static_index = ? and create_source_code = ? and time >= ? and time <= ?;';
        $ret = $this->objDao->select($query, [$static_index, $source, $start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret[0];
    }

}

?>
