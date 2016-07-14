<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file SourceCode.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/06/28 20:04:10
 * @brief 
 *  
 **/


namespace App\Models;

use App\Common\Util;
use DB;

class SourceCode
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
     * @var SourceCode
     * @return SourceCode 单例对象
     */
    public static function getInstance()
    {
        if (!self::$objInstance) {
            self::$objInstance = new self();
        }
        return self::$objInstance;
    }



    /**
     * 通过id获取来源详细信息
     * @param $id
     * @return array|null
     */
    public function getDetailById($id) {
        $query = 'select * from source_code where id = ? ;';
        $ret = $this->objDao->select($query, [$id]);
        if($ret == null){
            return null;
        }
        $ret = Util::stdToArray($ret);
        return $ret[0];
    }


}




?>
