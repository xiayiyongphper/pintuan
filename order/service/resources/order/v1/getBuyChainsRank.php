<?php
/**
 * Created by order.
 * User: Ryan Hong
 * Date: 2018/8/2
 * Time: 17:50
 */

namespace service\resources\order\v1;

use common\models\Order;
use message\order\BuyChainsRankReq;
use message\order\BuyChainsRankRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;

/**
 * Class getBuyChainsRank
 * @package service\resources\order\v1
 */
class getBuyChainsRank extends ResourceAbstract
{
    /** @var  BuyChainsRankReq */
    protected $request;

    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param string $bytes
     * @return mixed
     */
    public function run($data)
    {
        $this->doInit($data);

        $orderNumber = $this->request->getOrderNumber();
        $order = Order::findOne(['order_number' => $orderNumber]);
        if(!$order){
            Exception::throwException(Exception::ORDER_NOT_EXIST);
        }

        if($order->type != 4){
            Exception::throwException(Exception::ORDER_NOT_BUY_CHAINS);
        }

        if(in_array($order->status,[Order::STATUS_UNPAID,Order::STATUS_CANCELED])){
            Exception::throwException(Exception::ORDER_NOT_VALID);
        }

        $rank = Order::find()->where(['buy_chains_id' => Order::TYPE_BUY_CHAINS])
            ->andWhere(['not in','status',[Order::STATUS_UNPAID,Order::STATUS_CANCELED]])
            ->andWhere(['<','pay_at',$order->pay_at])
            ->count();

        $rank++;

        $this->response->setFrom(['rank' => $rank]);
        return $this->response;
    }

    public static function request()
    {
        return new BuyChainsRankReq();
    }

    public static function response()
    {
        return new BuyChainsRankRes();
    }
}