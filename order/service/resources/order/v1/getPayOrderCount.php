<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use framework\components\ToolsAbstract;
use common\models\Order;
use message\order\GetPayOrderCountReq;
use message\order\GetPayOrderCountRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

class getPayOrderCount extends ResourceAbstract
{

    public function run($data)
    {
        $this->doInit($data);
        $request = $this->request;
        $count = Order::getPayOrderCount($request->getUserId(), $request->getStoreId());
        $response = self::response();
        $response->setOrderCount(intval($count));
        return $response;
    }

    public static function request()
    {
        return new GetPayOrderCountReq();
    }

    public static function response()
    {
        return new GetPayOrderCountRes();
    }
}
