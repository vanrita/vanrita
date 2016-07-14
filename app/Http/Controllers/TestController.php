<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/



/**
 * @file TestController.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/03/21 13:59:03
 * @brief 
 *  
 **/

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use Cache;

class TestController extends BaseController {

    /**
     * 默认路由
     */
    public function getIndex(){
        $view = view('alldata');
        return $view;
        return $this->setResponse(0, 'success');
    }

    public function getTest() {

    }

    /**
     * post入口
     */
    public function postTest() {
        $key = "test123";
        return $key;
    }

}


?>
