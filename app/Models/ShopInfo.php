<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file ShopInfo.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/06/28 15:31:20
 * @brief 
 *  
 **/


namespace App\Models;

use App\Common\Util;
use DB;

class ShopInfo
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
     * @var ShopInfo
     * @return ShopInfo 单例对象
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
    public function getAllByTime($start_time, $end_time) {
        $query = 'select count(*) from shop_info where time >= ? and time <= ? ;';
        $ret = $this->objDao->select($query, [$start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret[0];
    }

    /**
     * 按时间获取各渠道门店数
     * @param $start_time
     * @param $end_time
     * @return array|null
     */
    public function getCatagoryShopsByTime($start_time, $end_time) {
        $query = 'select create_source_code, count(*) from shop_info
                  where time >= ? and time <= ? group by create_source_code;';
        $ret = $this->objDao->select($query, [$start_time, $end_time]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret;
    }

    /**
     * 添加db操作
     * @param $data
     * @return int
     */
    public function addDbOperate($data) {
        $create_time =  \Carbon\Carbon::now();
        $ret = DB::table('operation_info')->insertGetId(
            [   'pid'         => $data['pid'],
                'operation'   => $data['operation'],
                'type'        => $data['type'],
                'param'       => $data['param'],
                'db_config'   => $data['db_config'],
                'status'      => 1,
                'create_time' => $create_time,
                'update_time' => $create_time,
            ]
        );
        return $ret;
    }

    /**
     * 添加api操作
     * @param $data
     * @return int
     */
    public function addApiOperate($data) {
        $create_time =  \Carbon\Carbon::now();
        $ret = DB::table('operation_info')->insertGetId(
            [   'pid'         => $data['pid'],
                'operation'   => $data['operation'],
                'type'        => $data['type'],
                'method'      => $data['method'],
                'param'       => $data['param'],
                'status'      => 1,
                'create_time' => $create_time,
                'update_time' => $create_time,
            ]
        );
        return $ret;
    }
}

?>
