<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Index.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/06/23 21:30:20
 * @brief 
 *  
 **/

namespace App\Http\Controllers\Summary;

use App\Logic\Summary\ShopLogic;

class Index extends Base {

    /**
     * 获取首页模板
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $time = date("Y-m-d");
        $objLogic = new ShopLogic();
        $data = $objLogic->summaryData($time);
        $view = view('Summary.alldata');
        $view -> with('data', $data);
        return $view;
    }


    /**
     * 获取所有联调记录
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getGetall() {
        $time = date("Y-m-d");
        $objLogic = new ShopLogic();
        $ret = $objLogic->summaryData($time);
        return $this->setResponse($ret);
    }

    /**
     * 获取漏斗指标数据
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getFunnel() {
        $this->isAjax();
        $params = array('type');
        $data = $this->checkParams($params);
        $objLogic = new ShopLogic();
        $ret = $objLogic->getFunnelData($data['type']);
        return $this->setResponse($ret);
    }

    /**
     * 获取多天漏斗指标数据
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getFunnelmore() {
        $this->isAjax();
        $params = array('type', 'start_time', 'end_time');
        $data = $this->checkParams($params);
        $time = array(
            'start_time' => $data['start_time'],
            'end_time'   => $data['end_time'],
        );
        $objLogic = new ShopLogic();
        $ret = $objLogic->getFunnelData($data['type'], $time);
        return $this->setResponse($ret);
    }

}

?>
