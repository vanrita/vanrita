<?php
/**
 * Created by PhpStorm.
 * User: liutong08
 * Date: 16/7/6
 * Time: 下午20:14
 */
namespace App\Logic\Material;

use App\Models\Material;
use Log;
use App\Logic\Base\Ret;

class MaterialLogic {

    protected $objMaterial;

    public function __construct() {
        $this->objMaterial = Material::getInstance();
    }

    /**
     * 根据日期和物料类型返回shop数量
     * @param $date
     * @return array|null
     */
    public function getFeatureShopNumByDate($date) {
        $shopInfo = $this->objMaterial->getFeatureShopNumByDate($date);
        if (empty($shopInfo)) {
            $ret = array(
                'status' => Ret::DATA_NULL,
                'msg'    => Ret::getMessage(Ret::DATA_NULL),
                'data'   => null,
            );
            return $ret;
        }

        $shopNum = array();
        foreach ($shopInfo as $item) {
            $featureNum = $item['template_tese_cnt'];
            $featureArea = $item['shop_tese_cnt'];

            if ($featureArea <= 1) {
                if (array_key_exists($featureNum, $shopNum)) {
                    $shopNum[$featureNum][0] += 1 ;
                } else {
                    $tmp = array($featureNum => array(1, 0, 0, 0));
                    array_push($shopNum, $tmp['1']);
                }
            } elseif ($featureArea <= 3){
                if (array_key_exists($featureNum, $shopNum)) {
                    $shopNum[$featureNum][1] += 1;
                } else {
                    $tmp = array($featureNum => array(0, 1, 0, 0));
                    array_push($shopNum, $tmp['1']);
                }
            } elseif ($featureArea <= 5) {
                if (array_key_exists($featureNum, $shopNum)) {
                    $shopNum[$featureNum][2] += 1;
                } else {
                    $tmp = array($featureNum => array(0, 0, 1, 0));
                    array_push($shopNum, $tmp['1']);
                }
            } else {
                if (array_key_exists($featureNum, $shopNum)) {
                    $shopNum[$featureNum][3] += 1;
                } else {
                    $tmp = array($featureNum => array(0, 0, 0, 1));
                    array_push($shopNum, $tmp['1']);
                }
            }

        }
        #Log::notice(array(
        #    'test' => test,
        #));
        $feature = array();
        foreach ($shopNum as $key => $value) {
            array_push($feature, '模板特色数-' . $key);
        }
        $afterShopNum = array();

        for ($i=0; $i<count($shopNum); $i++) {
            for ($j=0; $j<count($shopNum[$i]); $j++) {
                $afterShopNum[$j][$i] = $shopNum[$i][$j];
            }
        }
        $ret = array (
            'category' => $feature,
            'data'     => $afterShopNum
        );
        return $ret;
    }

    /**
     * 根据日期和物料类型返回shop数量
     * @param $date
     * @return array|null
     */
    public function getServiceShopNumByDate($date) {
        $shopInfo = $this->objMaterial->getServiceShopNumByDate($date);
        if (empty($shopInfo)) {
            $ret = array(
                'status' => Ret::DATA_NULL,
                'msg'    => Ret::getMessage(Ret::DATA_NULL),
                'data'   => null,
            );
            return $ret;
        }

        $shopNum = array();
        foreach ($shopInfo as $item) {
            $serviceNum = $item['template_xiangmu_cnt'];
            $serviceArea = $item['shop_xiangmu_cnt'];

            if ($serviceArea <= 1) {
                if (array_key_exists($serviceNum, $shopNum)) {
                    $shopNum[$serviceNum][0] += 1 ;
                } else {
                    $tmp = array($serviceNum => array(1, 0, 0, 0));
                    array_push($shopNum, $tmp['1']);
                }
            } elseif ($serviceArea <= 3) {
                if (array_key_exists($serviceNum, $shopNum)) {
                    $shopNum[$serviceNum][1] += 1;
                } else {
                    $tmp = array($serviceNum => array(0, 1, 0, 0));
                    array_push($shopNum, $tmp['1']);
                }
            } elseif ($serviceArea <= 5) {
                if (array_key_exists($serviceNum, $shopNum)) {
                    $shopNum[$serviceNum][2] += 1;
                } else {
                    $tmp = array($serviceNum => array(0, 0, 1, 0));
                    array_push($shopNum, $tmp['1']);
                }
            } else {
                if (array_key_exists($serviceNum, $shopNum)) {
                    $shopNum[$serviceNum][3] += 1;
                } else {
                    $tmp = array($serviceNum => array(0, 0, 0, 1));
                    array_push($shopNum, $tmp['1']);
                }
            }

        }
        #Log::notice(array(
        #    'test' => test,
        #));
        $service = array();
        foreach ($shopNum as $key => $value) {
            array_push($service, '模板服务项目数-' . $key);
        }
        $afterShopNum = array();

        for ($i=0; $i<count($shopNum); $i++) {
            for ($j=0; $j<count($shopNum[$i]); $j++) {
                $afterShopNum[$j][$i] = $shopNum[$i][$j];
            }
        }
        $ret = array (
            'category' => $service,
            'data'     => $afterShopNum
        );
        return $ret;
    }

    /**
     * 根据日期和物料类型返回shop数量
     * @param $date
     * @return array|null
     */
    public function getAllShopNumByDate($date) {
        $shopInfo = $this->objMaterial->getAllShopNumByDate($date);
        if(empty($shopInfo)) {
            $ret = array(
                'status' => Ret::DATA_NULL,
                'msg'    => Ret::getMessage(Ret::DATA_NULL),
                'data'   => null,
            );
            return $ret;
        }

        $shopNum = array();
        foreach ($shopInfo as $item) {
            $serviceArea = $item['shop_xiangmu_cnt'] + $item['shop_tese_cnt'];

            if ($serviceArea <= 1) {
                if (array_key_exists('装修总数<=1', $shopNum)) {
                    $shopNum['装修总数<=1'] += 1;
                } else {
                    $shopNum['装修总数<=1'] = 1;
                }
            } elseif ($serviceArea <= 3) {
                if (array_key_exists('装修总数<=3', $shopNum)) {
                    $shopNum['装修总数<=3'] += 1;
                } else {
                    $shopNum['装修总数<=3'] = 1;
                }
            } elseif ($serviceArea <= 5) {
                if (array_key_exists('装修总数<=5', $shopNum)) {
                    $shopNum['装修总数<=5'] += 1;
                } else {
                    $shopNum['装修总数<=5'] = 1;
                }
            } else {
                if (array_key_exists('装修总数>5', $shopNum)) {
                    $shopNum['装修总数>5'] += 1;
                } else {
                    $shopNum['装修总数>5'] = 1;
                }
            }

        }
        #Log::notice(array(
        #    'test' => test,
        #));
        $area = array();
        $num = array();
        foreach ($shopNum as $key => $value) {
            array_push($area,$key);
            array_push($num,$value);
        }
        $ret = array (
            'category' => $area,
            'data'     => $num
        );
        return $ret;
    }

}