<?php

/**
 * HTTP请求，封装CURL
 *
 * 支持GET/POST请求
 *
 * 可通过static setOption方法设置curl_setopt_array的各参数
 * 并提供常用option设置的封装：
 *  post
 *  timeout(连接超时时间)
 *  nossl
 *  auth
 *
 * 若网络交互出错，可通过errMsg/errno方法获取具体错误原因和错误码
 */

class Http {

    /**
     * 常量：失败后不再重连
     * @var boolean
     */
    const CONNECT_ONCE = true;

    /**
     * curl可接受的配置
     * @var
     */
    protected static $arrConfs;

    /**
     * 请求服务器信息
     * @var array
     */
    protected $arrServerInfor;

    /**
     * 当前连接使用的url，主要用于日志记录
     * @var string
     */
    protected $strCurUrl;

    /**
     * 连接服务器curl对象的句柄
     * @object
     */
    protected $objHandle;

    /**
     * 构造函数
     *
     * @param array $arrServers
     * @example
     *  $arrServers = array(
     *      'flag' => 0,
     *      'hosts' => array(
     *          '10.23.247.6:8608',
     *          '10.23.247.22:8608',
     *       ),
     *      'path' => '/services/LoginService.php',
     *      'remote' => array(
     *          'http://10.23.247.6:8608/services/LoginService.php',
     *          'http://10.23.247.22:8608/services/LoginService.php',
     *       ),
     *  );
     */
    protected function __construct($arrServers = null) {
        if (isset($arrServers)) {
            $this->arrServerInfor = $arrServers;
        } else {
            $this->arrServerInfor = array();
        }

        $this->objHandle = curl_init();
        curl_setopt($this->objHandle, CURLOPT_RETURNTRANSFER, 1);
        if (isset(self::$arrConfs)){
            curl_setopt_array($this->objHandle, self::$arrConfs);
        }
    }

    /**
     * 析构函数
     *
     */
    public function __destruct() {
        curl_close($this->objHandle);
        unset($this->objHandle);
        unset($this->arrServerInfor);
    }

    /**
     * __construct的封装，非单例
     * 便于串联多个调用
     *
     * @return Http
     */
    public static function instance($arrServers = null) {
        return new self($arrServers);
    }

    /**
     * 设置curl_setopt_array
     *
     * @param array $options
     * @return boolean
     */
    public function setOptions($options) {
        if (empty($options) || !is_array($options)) {
            return false;
        }

        return true;
    }

    /**
     * 默认的设置，curl_setopt_array
     *
     * @param array $arrConf
     */
    public static function setOption(array $arrConf) {
        self::$arrConfs = $arrConf;
    }

    /**
     * 执行请求
     *
     * @param int $intMaxtry        重试次数; 传引用，返回为执行下标，从0开始。若多次调用，每次需初始化
     * @param bool $bolConnectOnce  连接成功后，若因为其他原因失败，不再重试
     *
     * @return string|false
     */
    public function exec($intMaxtry = 1, $bolConnectOnce = false) {
        // 检查重试次数
        if ($intMaxtry <= 0){
            throw new Exception("Maxtry fail: $intMaxtry");
        }
        // 读取URL信息
        $strRet = false;
        $bolFromHosts = false;
        $arrHosts = null;
        if (array_key_exists('hosts', $this->arrServerInfor)) {
            $arrHosts = &$this->arrServerInfor['hosts'];
        }
        $strPath = null;
        if (array_key_exists('path', $this->arrServerInfor)) {
            $strPath = $this->arrServerInfor['path'];
        }
        $arrRemote = null;
        if (array_key_exists('remote', $this->arrServerInfor)) {
            $arrRemote = &$this->arrServerInfor['remote'];
        }
        for ($intRetry = 0; $intRetry < $intMaxtry; $intRetry++) {
            // 获取访问的URL
            if (is_array($arrHosts) && count($arrHosts) > 0) {
                $intIndex = array_rand($arrHosts);
                $this->strCurUrl = $arrHosts[$intIndex] . $strPath;
                $bolFromHosts = true;
            } else if (is_array($arrRemote) && count($arrRemote) > 0) {
                $intIndex = array_rand($arrRemote);
                $this->strCurUrl = $arrRemote[$intIndex];
                $bolFromHosts = true;
            } else {
                throw new Exception("url not init");
            }

            // 设置访问的URL
            curl_setopt($this->objHandle, CURLOPT_URL, $this->strCurUrl);
            // 执行调用
            $strRet = curl_exec($this->objHandle);
            Log::notice(array(
                'strRet' => $strRet,
                'length' => strlen($strRet),
            ));
            // 访问成功后退出
            if ($strRet !== false) {
                break;
            }
            if ($this->errno() <= CURLE_COULDNT_CONNECT || $this->errno() == 28) {
                /* 无法连接服务器 */
                if ($bolFromHosts) {
                    unset($arrHosts[$intIndex]);
                } else {
                    unset($arrRemote[$intIndex]);
                }

                // 若已没有可用host，不再重试
                if (count($arrHosts) <= 0 && count($arrRemote) <= 0) {

                    break;
                }
            } elseif($bolConnectOnce){
                /* 链接成功后其它错误，不再重试 */
                break;
            }
        }
        $intMaxtry = $intRetry;
        return $strRet;
    }

    /**
     * 设置请求地址
     *
     * @param $mixHost  array | string，若为array，则exec时，会随机取host使用
     * @param $strPath
     * @param integer @intFlag
     *
     * 建议若传入单个host时，改为使用instance。
     *
     * @example
     * <code>
     *  $arrHost = array(
     *      '127.0.0.1:8000',
     *      '127.0.0.1:8001',
     *  );
     *  $strPath = '/services/LoginService.php';
     *  Http::instance()->url($arrHost, $strPath)->exec();
     * </code>
     *
     * <code>
     *  Http::instance()->url('http://10.23.247.6:8608',
     *                                      '/services/LoginService.php')->exec();
     * </code>
     *
     * @return Http
     */
    public function url($mixHost, $strPath, $intFlag = 0) {
        if(is_array($mixHost)){
            $this->arrServerInfor['hosts'] = array_values($mixHost);
            $this->arrServerInfor['path'] = $strPath;
            unset($this->arrServerInfor['remote']);
        } else {
            $this->arrServerInfor['remote'][0] = $mixHost . $strPath;
            unset($this->arrServerInfor['hosts']);
            unset($this->arrServerInfor['path']);
        }
        $this->arrServerInfor['flag'] = $intFlag;
        return $this;
    }

    /**
     * 设置POST参数
     *
     * @param array $arrParam POST参数数组
     * @return Http
     */
    public function post(Array $arrParam = null) {
        if($arrParam){
            $strParam = http_build_query($arrParam, 'param_');
            curl_setopt($this->objHandle, CURLOPT_POSTFIELDS, $strParam);
        }
        else{
            curl_setopt($this->objHandle, CURLOPT_POST, true);
        }
        return $this;
    }

    /**
     * 设置超时时间
     *
     * @param $mixTime int | float 单位：秒
     *
     * @return Http
     */
    public function timeout($mixTime) {
        if(is_float($mixTime)){
            curl_setopt($this->objHandle, CURLOPT_TIMEOUT_MS, (int) $mixTime * 1000);
        } else{
            curl_setopt($this->objHandle, CURLOPT_TIMEOUT, (int) $mixTime);
        }
        return $this;
    }

    /**
     * 取消SSL的设置
     *
     * @return Http
     */
    public function nossl() {
        curl_setopt($this->objHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->objHandle, CURLOPT_SSL_VERIFYHOST, FALSE);
        return $this;
    }

    /**
     * 设置HTTP基本认证
     *
     * @return Http
     */
    public function auth($strUname, $strPwd) {
        curl_setopt($this->objHandle, CURLOPT_USERPWD, "$strUname:$strPwd");
        return $this;
    }

    /**
     * 读取错误消息
     *
     * @return string
     */
    public function errMsg() {
        return curl_error($this->objHandle);
    }

    /**
     * 读取错误消息的代码
     *
     * @return int
     */
    public function errno() {
        return curl_errno($this->objHandle);
    }

    /**
     * 传递cookie参数
     * @param String $key
     * @param String $value
     * @return int
     */
    public function cookie($key, $value) {
        curl_setopt($this->objHandle, CURLOPT_COOKIE, "$key=$value");
        return $this;
    }
}
