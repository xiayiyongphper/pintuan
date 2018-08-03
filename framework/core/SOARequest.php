<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 16:13
 */

namespace framework\core;

use framework\components\es\Console;
use framework\components\ToolsAbstract;
use framework\Exception;
use framework\message\Message;
use framework\models\dau\DauInterface;
use framework\models\dau\Factory;
use framework\resources\ApiAbstract;
use service\message\common\Header;
use service\message\common\SourceEnum;

/**
 * Class SOARequest
 * @package framework\core
 */
class SOARequest extends SWRequest
{

    /**
     * pb header
     * @var Header
     */
    private $pbHeader = null;

    /**
     * @var Message
     */
    protected $message;

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
        'sales.orderComment' => [1, 3],
        'sales.orderDetail' => [1, 3],
    ];

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @inheritdoc
     */
    public function setDebug($isDebug = false)
    {
        if (is_bool($isDebug)) {
            $this->debug = $isDebug;
            $header = $this->message->getHeader();
            $this->level = ToolsAbstract::getCustomerDebugLevel($header->getCustomerId());
            if (!$this->level) {
                $this->level = 1;
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTraceId()
    {
        return str_replace('.', '', uniqid(ENV_SYS_NAME . '_', true));
    }

    /**
     * @inheritdoc
     */
    public function resolve()
    {
        $rawBody = $this->getRawBody();
        $this->message = new Message();
        $this->message->unpack($rawBody);
        $header = $this->message->getHeader();
        $params = $this->message->getPackageBody();

        return [$header, $params];
    }

    /**
     * @return Header
     */
    public function getPbHeader(): Header
    {
        return $this->pbHeader;
    }
}