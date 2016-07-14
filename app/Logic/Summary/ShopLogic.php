<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file ShopLogic.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/06/28 15:50:38
 * @brief 
 *  
 **/

namespace App\Logic\Summary;

use App\Common\Util;
use App\Models\FunelIndex;
use App\Models\QualityFunnel;
use App\Models\ShopIncDec;
use App\Models\SourceCode;
use Log;
use App\Logic\Base\Ret;


class ShopLogic
{

    const ONE_DAY_STATMP = 86400;

    protected $objShopInfo;
    protected $objQualityFunnel;

    /**
     * ShopLogic constructor.
     */
    public function __construct() {
        $this->objShopIncDec = ShopIncDec::getInstance();
        $this->objQualityFunnel = QualityFunnel::getInstance();
    }

    /**
     * 获取概览数据
     * @return array
     */
    public function summaryData() {
        $allShops = $this->getAllShops();
        $categoryData = $this->getCatagoryShops();
        if($categoryData == null || $allShops == null) {
            $ret = array(
                'status' => Ret::DATA_NULL,
                'msg'    => Ret::getMessage(Ret::DATA_NULL),
                'data'   => null,
            );
            $ret = array(
                [
                    'title' => '总门店数',
                    'sum'   => 100000,
                    'type'  => 0,
                ],
                [
                    'title' => 'APP来源门店数',
                    'sum'   => 1000,
                    'type'  => 200,
                ],
                [
                    'title' => 'PC来源门店数',
                    'sum'   => 111,
                    'type'  => 102,
                ],
                [
                    'title' => 'SSO来源门店数',
                    'sum'   => 123,
                    'type'  => 101,
                ],
                [
                    'title' => 'OPEN2来源门店数',
                    'sum'   => 11,
                    'type'  => 102,
                ],
            );
            return $ret;
        }
        $objSourceCode = SourceCode::getInstance();
        $ret = array();
        $ret['all'] = $allShops['shop'];
        foreach ($categoryData as $item) {
            $detail = $objSourceCode->getDetailById($item['create_source_code']);
            $ret[$detail['name']] = $item['shop'];
        }
        return $ret;
    }


    /**
     * 获取全部店铺
     * @param $time
     * @return array
     */
    public function getAllShops($time = null) {
        if($time == null) {
            $strTime = strtotime(date("Y-m-d"));
            $start_time = date('Y-m-d H:i:s', $strTime - self::ONE_DAY_STATMP);
            $end_time = date('Y-m-d H:i:s', $strTime);
        } else {
            $start_time = $time['start_time'];
            $strTime = strtotime($time['end_time']);
            $end_time = date('Y-m-d H:i:s', $strTime + self::ONE_DAY_STATMP);
        }
        $ret = $this->objShopIncDec->getAllByTime($start_time, $end_time);
        return $ret;
    }

    /**
     * 获取分类店铺数据
     * @param $time
     * @return array
     */
    public function getCatagoryShops($time = null) {
        if($time == null) {
            $strTime = strtotime(date("Y-m-d"));
            $start_time = date('Y-m-d H:i:s', $strTime - self::ONE_DAY_STATMP);
            $end_time = date('Y-m-d H:i:s', $strTime);
        } else {
            $start_time = $time['start_time'];
            $strTime = strtotime($time['end_time']);
            $end_time = date('Y-m-d H:i:s', $strTime + self::ONE_DAY_STATMP);
        }
        $ret = $this->objShopIncDec->getAllSourceByTime($start_time, $end_time);
        return $ret;
    }

    /**
     * 获取全局漏斗数据及流失率计算
     * @param $time
     * @param $type
     * @return array|null
     */
    public function getFunnelData($type, $time = null) {
        if($time == null) {
            $strTime = strtotime(date("Y-m-d"));
            $start_time = "2016-06-30";
            $end_time = "2016-07-01";
            //$start_time = date('Y-m-d H:i:s', $strTime - self::ONE_DAY_STATMP);
            //$end_time = date('Y-m-d H:i:s', $strTime);
        } else {
            $start_time = $time['start_time'];
            $strTime = strtotime($time['end_time']);
            $end_time = date('Y-m-d H:i:s', $strTime + self::ONE_DAY_STATMP);
        }
        if($type == '0') {
            $data = $this->objQualityFunnel->getAllByTime($start_time, $end_time);
        } else {
            $data = $this->objQualityFunnel->getAllByTimeAndSource($type, $start_time, $end_time);
        }
        if($data == null) {
            $ret = array(
                'status' => Ret::DATA_NULL,
                'msg'    => Ret::getMessage(Ret::DATA_NULL),
                'data'   => null,
            );
            return $ret;
        }
        $formData = array();
        foreach($data as $item) {
            if(!isset($formData[$item['day']])) {
                $formData[$item['day']] = array();
            }
            array_push($formData[$item['day']], $item);
        }
        $objFunelIndex = FunelIndex::getInstance();
        $ret = array();
        foreach($formData as $day=>$data) {
            $ret[$day] = array();
            $sum = $data[0]['sum'];
            for($i = 0; $i < count($data); $i++) {
                $key = array();
                $item = $data[$i];
                $key['id'] = $item['static_index'];
                $key['sum'] = $item['sum'];
                $detail = $objFunelIndex->getDetailById($item['static_index']);
                $key['name']  = $detail['name'];
                $key['rate'] = round($item['sum'] / $sum, 2);
                if($i !== 0) {
                    $item2 = $data[$i - 1];
                    $key['radio'] =  Util::calRadio($item2['sum'], $item['sum']);
                }
                if($i < count($data) -1) {
                    $item3 = $data[$i + 1];
                    $key['reduce'] = $item['sum'] - $item3['sum'];
                }
                array_push($ret[$day], $key);
            }
        }
        return $ret;
    }


}

?>
