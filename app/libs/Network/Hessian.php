<?php

require_once dirname(__FILE__).'/Hessian/HessianOptions.php';
require_once dirname(__FILE__).'/Hessian/HessianClient.php';

/**
 * Hessian客户端
 *
 * @author zhangwei51<zhangwei51@baidu.com>
 */

class Hessian
{

    /**
     * 默认返回data的内容 而非原始的raw数据
     * 对于非Chip框架下的RPC交互，子类可以设置为true来返回原始数据
     *
     * @var boolean
     */
    protected $rawData = false;

    /**
     * 服务的URL地址
     *
     * @var string
     */
    protected $url;

    /**
     * 服务的配置信息
     *
     * @var array
     */
    protected $conf = array();

    /**
     * HessianClient代理对象
     *
     * @var HessianClient
     */
    protected $proxy = null;

    /**
     * Hessian 版本号
     *
     * @var integer
     */
    protected $version = 2;

    /**
     * 重试次数
     *
     * @var integer
     */
    protected $retry = 2;

    /**
     * 错误信息
     *
     * @var string
     */
    protected $error = '';

    /**
     * 错误码
     *
     * @var integer
     */
    protected $errno = 0;

    /**
     * RPC的hessian客户端
     *
     * @param string $url 访问URL
     */
    public function __construct($url)
    {
        $this->url = $url;
        if (!empty($this->conf['retry'])) {
            $this->retry = $this->conf['retry'];
        }
        if (isset($this->conf['version'])) {
            $this->version = $this->conf['version'];
        }
    }

    /**
     * 获取返回值data 并同时设置error信息 <br>
     * 子类可以重写该方法实现自定义的返回值，这样可以应对各类接口的返回值
     *
     * @param object $res
     * @return mixed
     */
    protected function outputData($res)
    {
        $this->errno = $res['status'];
        $this->error = empty($res['info']) ? '' : $res['info'];
        return $res['data'];
    }

    /**
     * 初始化Proxy
     */
    protected function init()
    {
        if ($this->proxy == null) {
            $this->connect();
        }
    }

    /**
     * 联接远程hessian服务
     */
    protected function connect()
    {
        $option = new HessianOptions();
        $option->version = $this->version;
        //$option->saveRaw = true;
        $this->proxy = new HessianClient($this->getUrl(), $option);
    }

    /**
     * 获取hessian接口url
     *
     * @return string
     */
    private function getUrl()
    {
        if (!is_array($this->url)) {
            return $this->url;
        }

        $key = array_rand($this->url);
        return $this->url[$key];
    }

    /**
     * 进行远程方法调用
     *
     * @param string $name
     * @param array $param
     * @return mixed
     */
    public function __call($name, $param)
    {
        $this->init();
        for ($i = 0; $i < $this->retry; $i ++) {
            try {
                return $this->doCall($name, $param);
            } catch (Exception $ex) {
                Log::error(array(
                    'msg' => 'RPC failed',
                    'info' => $ex->getMessage(),
                ));
                $this->connect();
                $this->errno = 1;
                $this->error = 'Service fail:' . $ex->getMessage();
            }
        }
        return null;
    }

    /**
     * 执行一次远程调用
     *
     * @param string $name
     * @param array $param
     * @return mixed
     */
    private function doCall($name, $param)
    {
        $callback = array($this->proxy, $name);
        $res = call_user_func_array($callback, $param);
        if ($this->rawData) {
            return $res;
        }

        return $this->outputData($res);
    }

    /**
     * 获取最后的错误信息
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * 获取最后的错误码
     *
     * @return integer
     */
    public function errno()
    {
        return $this->errno;
    }
}