<?php

/**
 * uuap接口
 */
require_once(__DIR__ . '/CAS.php');

/**
 * UUAP入口类
 * 
 */
Class UUAP_Index {
    /**
     * 检验是否登录
     * @param void
     * @return string 用户名
     */
    public static function checkLogin() {

        global $g_strUsername;

        // Uncomment to enable debugging
        phpCAS::setDebug();
        $cas_host = env('CAS_HOST');
        $cas_port = env('CAS_PORT');
        $cas_context = env('CAS_CONTEXT');
        $cas_port = intval($cas_port);

        // Initialize phpCAS
        phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

        // For production use set the CA certificate that is the issuer of the cert
        // on the CAS server and uncomment the line below
        // phpCAS::setCasServerCACert($cas_server_ca_cert_path);

        // For quick testing you can disable SSL validation of the CAS server.
        // THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
        // VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
        phpCAS::setNoCasServerValidation();

        // force CAS authentication
        phpCAS::forceAuthentication();

        $g_strUsername = phpCAS::getUser();

        return $g_strUsername;
    }

    /**
     * 退出
     * @param void
     * @return void
     */
    public static function logout() {
        self::checkLogin();
        phpCAS::logout();
    }

    /**
     * 根据用户名获取用户信息
     * @param string $userName
     * @return bool|object
     */
    public static function getUserByUsername($userName) {

        //WSDL文件的地址
        $wsdluri = env('UIC_URL');
        $appKey = env('UIC_KEY');

        //SoapHeader（命名空间，关键字--appKey--无需变化，appKey对应的值,false)
        try {
            $soapheader = new SoapHeader('http://schemas.xmlsoap.org/wsdl/soap/', 'appKey', $appKey, false);

            $soapclient = new SoapClient($wsdluri);
            $soapclient->__setSoapHeaders(array($soapheader));

            //发出请求调用
            $arrParams = array('arg0' => $userName);
            $mixRet = $soapclient->getUserByUsername($arrParams);
        } catch (Exception $e) {
            Log::error(array(
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
            ));
            return false;
        }

        return $mixRet;
    }
}

