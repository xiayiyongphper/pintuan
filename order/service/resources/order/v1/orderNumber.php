<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use common\models\Order;
use message\order\OrderNumberRequest;
use message\order\OrderNumberResponse;
use service\resources\ResourceAbstract;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 */
class orderNumber extends ResourceAbstract
{
    public function run($data)
    {
        $this->doInit($data);
        /** @var OrderNumberRequest $request */
        $request = $this->request;
        $response = self::response();
        //待付款
        $pending_pay_count = Order::find()->where(['user_id' => $request->getUserId()])
            ->andWhere(['del' => Order::NOT_DELETED])
            ->andWhere(['status' => Order::STATUS_UNPAID])
            ->count();
        $response->setPendingPay($pending_pay_count);
        //已经付款，待分享
        $pending_shipped_count = Order::find()->where(['user_id' => $request->getUserId()])
            ->andWhere(['del' => Order::NOT_DELETED])
            ->andWhere(['status' => Order::STATUS_PAID])
            ->andWhere(['enable_deliver_time'=>'0000-00-00 00:00:00'])
            ->count();
        $response->setToShare($pending_shipped_count);
        //已经付款，待发货
        $pending_shipped_count = Order::find()->where(['user_id' => $request->getUserId()])
            ->andWhere(['del' => Order::NOT_DELETED])
            ->andWhere(['status' => Order::STATUS_PAID])
            ->andWhere(['!=','enable_deliver_time','0000-00-00 00:00:00'])
            ->count();
        $response->setPendingShipped($pending_shipped_count);
        //已发货，待商家收货，待用户收货
        $pending_received_count = Order::find()->where(['user_id' => $request->getUserId()])
            ->andWhere(['del' => Order::NOT_DELETED])
            ->andWhere(['status' => [Order::STATUS_DELIVERED, Order::STATUS_ARRIVED]])
            ->count();
        $response->setPendingReceived($pending_received_count);
        //用户已经收货
        $user_received_count = Order::find()->where(['user_id' => $request->getUserId()])
            ->andWhere(['del' => Order::NOT_DELETED])
            ->andWhere(['status' => Order::STATUS_CONFIRMED])
            ->count();
        $response->setUserReceived($user_received_count);

        return $response;
    }

    public static function request()
    {
        return new OrderNumberRequest();
    }

    public static function response()
    {
        return new OrderNumberResponse();
    }

}
