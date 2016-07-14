<?php
/***************************************************************************
 *
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Base.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/05/16 19:30:53
 * @brief
 *
 **/

namespace App\Http\Controllers;

use App\Logic\Base\Ret;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Request;
use Response;
use Log;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 参数检查函数
     * @param $params
     * @return array
     */
    public function checkParams($params) {
        $ret = array();
        foreach($params as $param) {
            $ret[$param] = Request::input($param);
            if($ret[$param] === null) {
                $ret = array(
                    'status' => Ret::PARAM_EMPTY,
                    'msg'    => $param . Ret::getMessage(Ret::PARAM_EMPTY),
                );
                exit(json_encode($ret));
            }
        }
        return $ret;
    }

    /**
     * 记录log打点
     * @return array
     */
    public function getLog() {
        $data = $this->checkParams(array('action', 'type'));
        Log::info(array(
            'action' => $data['action'],
            'type'   => $data['type'],
        ));
        $ret = array(
            'status' => Ret::SUCCESS,
            'msg'    => Ret::getMessage(Ret::SUCCESS),
        );
        return $this->setResponse($ret);
    }

    /**
     * 规范返回
     * @param $ret
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setResponse($ret) {
        if(!isset($ret['status'])) {
            $ret = array(
                'status' => Ret::SUCCESS,
                'msg'    => Ret::getMessage(Ret::SUCCESS),
                'data'   => $ret,
            );
        }
        return Response::json($ret);
    }

    /**
     * 判断是否是ajax请求
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function isAjax() {
        if(!Request::ajax()) {
            $ret = array(
                'status' => Ret::AUTH_FORBIDDEN,
                'msg'    => Ret::getMessage(Ret::AUTH_FORBIDDEN),
                'data'   => null,
            );
            exit(json_encode($ret));
        }
    }

}
