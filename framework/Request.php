<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace framework;

use framework\components\es\Console;
use framework\components\ToolsAbstract;
use framework\message\Message;
use framework\models\dau\DauInterface;
use framework\models\dau\Factory;
use framework\resources\ApiAbstract;
use service\components\Tools;
use service\message\common\SourceEnum;

/**
 * The console Request represents the environment information for a console application.
 *
 * It is a wrapper for the PHP `$_SERVER` variable which holds information about the
 * currently running PHP script and the command line arguments given to it.
 *
 * @property array $params The command line arguments. It does not include the entry script name.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Request extends \yii\base\Request
{
    /**
     * @var Message
     */
    protected $_message;

    private $_rawBody;
    /**
     * @var int
     */
    private $_fd;

    /**
     * @var bool
     */
    protected $_remote;

    /**
     * @var
     */
    protected $_remoteIp;

    /**
     * debug mode
     * @var bool
     */
    protected $_debug = false;

    protected $_exception = false;

    protected $_level = 0;

    const REDIS_KEY_CUSTOMER_FD_TABLE = 'customer_fd_table';
    const REDIS_KEY_CONTRACTOR_FD_TABLE = 'contractor_fd_table';
    const REDIS_KEY_WHOLESALER_FD_TABLE = 'wholesaler_fd_table';
    const REDIS_KEY_DRIVER_FD_TABLE = 'driver_fd_table';

    /**
     * 频率控制
     * 'route'=>[window,size]
     * @var array
     */
    protected $_rateLimiter = [
        'sales.orderReview' => [1, 1],
        'sales.orderReview1' => [1, 1],
        'sales.createOrders' => [1, 1],
        'sales.createOrders1' => [1, 1],
        'sales.cancel' => [1, 1],
        'sales.decline' => [1, 1],
        'sales.orderComment' => [1, 1],
        'sales.orderDetail' => [1, 1],
    ];

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @return \framework\message\Message
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Sets the raw TCP request body
     * @param $rawBody
     * @return $this
     */
    public function setRawBody($rawBody)
    {
        $this->_rawBody = $rawBody;
        return $this;
    }

    public function getRawBody()
    {
        return $this->_rawBody;
    }

    public function setFd($fd)
    {
        $this->_fd = $fd;
        return $this;
    }

    public function getFd()
    {
        return $this->_fd;
    }

    /**
     * @return boolean
     */
    public function isRemote()
    {
        return $this->_remote;
    }

    /**
     * @param $remote
     * @return $this
     */
    public function setRemote($remote)
    {
        $this->_remote = $remote;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->_debug;
    }

    /**
     * @param bool $is_debug
     */
    public function setDebug($is_debug = null)
    {
        if ($is_debug !== null && is_bool($is_debug)) {
            $this->_debug = $is_debug;
            $header = $this->_message->getHeader();
            $this->_level = ToolsAbstract::getCustomerDebugLevel($header->getCustomerId());
            if (!$this->_level) {
                $this->_level = 1;
            }
        }
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * @return mixed
     */
    public function getRemoteIp()
    {
        return $this->_remoteIp;
    }

    /**
     * @param $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp)
    {
        $this->_remoteIp = $remoteIp;
        return $this;
    }


    /**
     * @return array
     */
    public function resolve()
    {
        $rawBody = $this->getRawBody();
        $this->_message = new Message();
        $this->_message->unpack($rawBody);
        $header = $this->_message->getHeader();
        //共享的redis
        $redis = ToolsAbstract::getRedis();
        $params = true;
        //接口版本验证
        if (ToolsAbstract::getSysName() !== 'route') {
            //不同系统不同路由，不同灰度
            $source = $header->getSource();
            switch ($source) {
                case SourceEnum::IOS_SHOP:
                case SourceEnum::ANDROID_SHOP:
                    $previewVersion = $redis->get(ApiAbstract::REDIS_KEY_PREVIEW_VERSION);
                    break;
                case SourceEnum::IOS_CONTRACTOR:
                case SourceEnum::ANDROID_CONTRACTOR:
                    $previewVersion = $redis->get(ApiAbstract::REDIS_KEY_PREVIEW_CONTRACTOR_VERSION);
                    break;
                default:
                    $previewVersion = $redis->get(ApiAbstract::REDIS_KEY_PREVIEW_VERSION);
                    break;
            }
            if (ENV_NODE_VERSION === ApiAbstract::VERSION_RELEASE) {
                //节点为正式版,测试版本APP访问了正式版本的服务器，需要返回错误码，通知客户端重新获取路由

                if ($previewVersion && version_compare($header->getAppVersion(), $previewVersion, '>=')) {
                    $params = new \Exception(Exception::SYSTEM_REDIRECTION_TEXT, Exception::SYSTEM_REDIRECTION);
                } else {
                    //previewVersion未设置，不做处理
                }
            } else {
                //节点为测试版，测试版本无限制
            }
        } else {
            //路由系统不用验证
        }

        //频率限制器
        if (isset($this->_rateLimiter[$header->getRoute()])) {
            if ($header->getCustomerId()) {
                //当用户id存在时，使用用户ID进行频率限制
                $identifier = sprintf('%s_%s', $header->getCustomerId(), $header->getRoute());
            } else {
                //当用户ID不存在时，使用连接ID进行频率限制
                $identifier = sprintf('fd_%s_%s', $this->getFd(), $header->getRoute());
            }
            list($window, $size) = $this->_rateLimiter[$header->getRoute()];
            if (!ToolsAbstract::rateLimiter($identifier, $window, $size)) {
                $params = new \Exception(Exception::RATE_LIMITER_FORBIDDEN_TEXT, Exception::RATE_LIMITER_FORBIDDEN);
            }
        }

        //Collectd::get()->report('pv', 1, [ENV_SYS_NAME, $header->getRoute()]);

        if ($header->getCustomerId() && ToolsAbstract::isCustomerDebug($header->getCustomerId())) {
            $this->setDebug(true);
        }

        // 记录fd
        if ($this->getFd()) {
            $fd = $this->getFd();
            if ($header->getCustomerId()) {
                $redis->hSet(self::REDIS_KEY_CUSTOMER_FD_TABLE, $header->getCustomerId(), $fd);
            } elseif ($header->getContractorId()) {
                $redis->hSet(self::REDIS_KEY_CONTRACTOR_FD_TABLE, $header->getContractorId(), $fd);
            } elseif ($header->getWholesalerId()) {
                $redis->hSet(self::REDIS_KEY_WHOLESALER_FD_TABLE, $header->getWholesalerId(), $fd);
            } elseif ($header->getDriverId()) {
                $redis->hSet(self::REDIS_KEY_DRIVER_FD_TABLE, $header->getDriverId(), $fd);
            }
        }

        $dau = Factory::getInstance($header);
        if ($dau instanceof DauInterface) {
            $dau->add();
        }

        if ($header->getCustomerId()) {
            Console::get()->log('app_request_log', null, [$this->_remoteIp, $header->getCustomerId(), $header->getRoute(), ENV_SERVER_IP]);
        } else {
            Console::get()->log('app_request_log', null, [$this->_remoteIp, $header->getRoute(), ENV_SERVER_IP]);
        }

        if (!$header->getTraceId()) {
            $header->setTraceId($this->getTraceId());
        }
        if ($params === true) {
            $params = $this->_message->getPackageBody();
        }

        return [$header, $params];
    }

    public function getTraceId()
    {
        return str_replace('.', '', uniqid(ENV_SYS_NAME . '_', true));
    }

}
