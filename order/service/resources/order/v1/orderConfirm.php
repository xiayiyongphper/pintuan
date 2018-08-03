<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use common\models\Order;
use message\order\OrderAction;
use service\resources\ResourceAbstract;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 */
class orderConfirm extends ResourceAbstract
{
    public function run($data)
    {
        $this->doInit($data);
        /** @var OrderAction $request */
        $request = $this->request;
        Order::orderConfirm($request->getUserId(), $request->getOrderId());
        return true;
    }

    public static function request()
    {
        return new OrderAction();
    }

    public static function response()
    {
        return true;
    }

}
