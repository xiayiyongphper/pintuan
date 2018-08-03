<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/28
 * Time: 9:51
 */

namespace framework\core;

use framework\message\Message;

/**
 * Class TaskRequest
 * @package framework\core
 */
class TaskRequest extends SWRequest
{
    /**
     * @inheritdoc
     */
    public function resolve()
    {
        $rawBody = $this->getRawBody();
        $message = new Message();
        $message->unpack($rawBody);

        $header = $message->getHeader();
        $body = $message->getPackageBody();

        $params = [];
        if (($message = json_decode($body, 1)) !== NULL) {
            $params = $message;
        }
        return [$header, $params];
    }
}