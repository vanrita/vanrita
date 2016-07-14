<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Ret.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/06/28 17:09:27
 * @brief 
 *  
 **/

namespace App\Logic\Base;

class Ret {

    const SUCCESS = 0;//成功

    //基本的错误信息
    const METHOD_NOT_ALLOWED = 405;
    const UNKNOWN            = 500; //未知错误
    const DB_ERROR           = 501; //数据库操作错误
    const OPERATE_NOT_EXIST  = 502; //操作不存在
    const OPERATE_FAILED     = 503; //操作失败
    const DATA_NULL          = 504; //结果为空

    const SYSTEM_ERROR   = 9999;
    const AUTH_FORBIDDEN = 10000;
    const PARAM_EMPTY    = 10001;
    const REQUEST_FAILED = 10002;
    const PARAM_ERROR    = 10003;
    const URL_TYPE_ERROR = 10004;

    /**
     * 错误码及对应错误信息
     */
    private static $_arrMessage = array(
        self::SUCCESS            => '操作成功',
        self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
        self::UNKNOWN            => '未知错误',
        self::DB_ERROR           => '数据库操作错误',
        self::OPERATE_NOT_EXIST  => '操作不存在',
        self::SYSTEM_ERROR       => '系统错误，请稍候再试。',
        self::AUTH_FORBIDDEN     => 'Permission denied',
        self::PARAM_EMPTY        => '参数不能为空',
        self::REQUEST_FAILED     => '请求失败',
        self::PARAM_ERROR        => '参数错误',
        self::URL_TYPE_ERROR     => 'URL地址不合法',
        self::DATA_NULL          => '无此数据',
        self::OPERATE_FAILED     => '操作失败',
    );

    /**
     * 获取错误信息
     *
     * @param int    $intErrNo
     * @return string
     */
    public static function getMessage($intErrNo) {
        if (isset(self::$_arrMessage[$intErrNo])) {
            return self::$_arrMessage[$intErrNo];
        }
        return self::$_arrMessage[self::UNKNOWN];
    }
}

?>
