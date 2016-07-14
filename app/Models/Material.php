<?php
/***************************************************************************
 *
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Material.php
 * @author liutong08(com@baidu.com)
 * @date 2016/07/06 19:57:28
 * @brief
 *
 **/


namespace App\Models;

use App\Common\Util;
use DB;

class Material
{

    protected $objDao;
    protected static $objInstance;

    /**
     * MaterialInfo constructor.
     */
    public function __construct()
    {
        $this->objDao = DB::connection();
    }

    /**
     *  * 单例模式
     * @var Material
     * @return Material
     */
    public static function getInstance()
    {
        if (!self::$objInstance) {
            self::$objInstance = new self();
        }
        return self::$objInstance;
    }


    /**
     * @param $date
     * @return array|null
     */
    public function getFeatureShopNumByDate($date) {
        $sql = 'select shop_id,template_tese_cnt,shop_tese_cnt from shop_info_' . $date . ' where deptname = \'北京分公司\'';
        $ret = $this->objDao->select($sql);
        $ret = Util::stdToArray($ret);
        if (count($ret) == 0) {
            $ret = null;
        }
        return $ret;
    }

    /**
     * @param $date
     * @return array|null
     */
    public function getServiceShopNumByDate($date) {
        $sql = 'select shop_id,template_xiangmu_cnt,shop_xiangmu_cnt from shop_info_' . $date . ' where deptname = \'北京分公司\'';
        $ret = $this->objDao->select($sql);
        $ret = Util::stdToArray($ret);
        if (count($ret) == 0) {
            $ret = null;
        }
        return $ret;
    }

    /**
     * @param $date
     * @return array|null
     */
    public function getAllShopNumByDate($date) {
        $sql = 'select shop_id,template_tese_cnt,shop_tese_cnt,template_xiangmu_cnt,shop_xiangmu_cnt from shop_info_' . $date . ' where deptname = \'北京分公司\'';
        $ret = $this->objDao->select($sql);
        $ret = Util::stdToArray($ret);
        if (count($ret) == 0) {
            $ret = null;
        }
        return $ret;
    }
}

?>