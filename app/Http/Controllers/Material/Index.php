<?php
/**
 * Created by PhpStorm.
 * User: liutong08
 * Date: 16/7/6
 * Time: 下午20:35
 */

namespace App\Http\Controllers\Material;

use App\Logic\Material\MaterialLogic;

use View;

class Index extends Base {

    public function __construct() {

    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */

    /**
     * 首页模板
     * @return view
     */
    public function getIndex(){


        //$view -> with('summaryData', $ret);

        #return $view;
    }

    /**
     * 根据日期获取服务特色店铺数
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getFeature(){
        $param = array('time');
        $data = $this->checkParams($param);
        $materialLogic = new MaterialLogic();
        $shopNum = $materialLogic ->getFeatureShopNumByDate($data['time']);
        return $this->setResponse($shopNum);
    }

    /**
     * 根据日期获取服务项目店铺数
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getService(){
        $param = array('time');
        $data = $this->checkParams($param);
        $materialLogic = new MaterialLogic();
        $shopNum = $materialLogic ->getServiceShopNumByDate($data['time']);
        return $this->setResponse($shopNum);
    }

    /**
     * 根据日期获取服务店铺数
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getShop(){
        $param = array('time');
        $data = $this->checkParams($param);
        $materialLogic = new MaterialLogic();
        $shopNum = $materialLogic ->getAllShopNumByDate($data['time']);
        return $this->setResponse($shopNum);
    }

}