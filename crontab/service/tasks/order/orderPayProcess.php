<?php
/**
 * Created by crontab.
 * User: Ryan Hong
 * Date: 2018/6/28
 * Time: 20:29
 */

namespace service\tasks\order;

use common\models\order\CommissionRecord;
use common\models\order\Order;
use common\models\order\OrderProduct;
use common\models\pintuan\Pintuan;
use common\models\pintuan\PintuanActivity;
use common\models\product\Product;
use common\models\user\User;
use common\models\wholesaler\Store;
use common\models\wholesaler\StoreCommission;
use framework\components\ToolsAbstract;
use framework\Exception;
use service\tasks\TaskService;

/**
 * Class orderPayProcess
 * @package service\tasks\order
 */
class orderPayProcess extends TaskService
{

    /**
     * @param mixed $data
     * @return mixed 如果不成功请抛异常；其他情况都是认为是成功的。
     */
    public function run($data)
    {
        ToolsAbstract::log($data, 'orderPayProcess.log');
        if (empty($data['order_id'])) {
            throw new \Exception("参数格式错误");
        }

        $orderId = $data['order_id'];
        $order = Order::findOne(['id' => $orderId]);
        if (!$order) {
            throw new \Exception("订单未找到");
        }
        ToolsAbstract::log($orderId, 'orderPayProcess.log');
        $orderProducts = OrderProduct::findAll(['order_id' => $orderId]);

        //更新用户是否有订单
        $this->updateUserInfo($order->user_id);

        //更新商品信息，更新拼团参与人数信息
        $this->updateProductAndPintuanInfo($order, $orderProducts);
        ToolsAbstract::log($order->type, 'orderPayProcess.log');
        //更新订单达到收货条件的时间 enable_deliver_time
        if ($order->type == 1) {//普通订单
            $order->enable_deliver_time = $order->pay_at;
            $order->save();
        } else {
            /** @var OrderProduct $orderProduct */
            $orderProduct = current($orderProducts);
            $pintuanId = $orderProduct->pintuan_id;
            $pintuan = Pintuan::findOne(['id' => $pintuanId]);
            if (!$pintuan){
                return false;
            }
            ToolsAbstract::log($pintuanId, 'orderPayProcess.log');
            if ($pintuan->become_group_status == Pintuan::BECOME_GROUP) {
                $order->enable_deliver_time = $order->pay_at;
                $order->save();
            } else {
                $pintuanActivity = PintuanActivity::findOne(['id' => $pintuan->pintuan_activity_id]);
                if ($pintuan->member_num >= $pintuanActivity->member_num) {//达到成团条件
                    $this->pintuanBecomeGroup($pintuan, $order->pay_at);
                }
            }
        }
        ToolsAbstract::log('calCommission', 'orderPayProcess.log');
        //生成佣金
        $this->calCommission($order);

        return true;
    }

    /**
     * @param Order $order
     */
    private function calCommission($order)
    {
        //加入小店佣金统计
        $store = Store::findOne(['id' => $order->store_id]);
        if (!$store) {
            return;
        }

        $commission_id = $store->commission_id;
        $commission = StoreCommission::findOne(['id' => $commission_id]);
        if (!$commission) {
            return;
        }
        $payable_amount = $order->payable_amount;
        $commission_type = $commission->commission_type;
        $commission_val = $commission->commission_val;

        switch ($commission_type) {
            case 1:
                $commission_amount = intval($payable_amount * $commission_val / 100);
                break;
            case 2:
                $commission_amount = $commission_val;
                break;
            default:
                $commission_amount = 0;
                break;
        }

        if ($commission_amount > 0) {
            $commissionRecord = new CommissionRecord();
            $commissionRecord->order_id = $order->id;
            $commissionRecord->store_id = $order->store_id;
            $commissionRecord->amount = $commission_amount;
            $commissionRecord->status = 2;//已获得
            $commissionRecord->create_at = ToolsAbstract::getDate();
            $commissionRecord->effect_at = ToolsAbstract::getDate();
            if (!$commissionRecord->save()) {
                ToolsAbstract::log($commissionRecord->errors, 'orderPayProcess.log');
            }
        }
    }


    /**
     * @param Pintuan $pintuan
     */
    protected function pintuanBecomeGroup($pintuan, $payTime)
    {
        $pintuan->become_group_status = pintuan::BECOME_GROUP;
        $pintuan->become_group_time = $payTime;
        $pintuan->save();

        $rabbitMq = ToolsAbstract::getRabbitMq();
        //发布成团的消息
        $data = [
            'route' => 'taskPintuan.pintuanBecomeGroupProcess',
            'params' => [
                'pintuan_id' => $pintuan->id,
            ],
        ];
        $rabbitMq->publish($data);
    }

    /**
     * 更新用户信息，是否下过单
     * @param $userId
     */
    protected function updateUserInfo($userId)
    {
        $user = User::findOne(['id' => $userId, 'has_order' => User::NOT_HAS_ORDER]);
        if ($user) {
            $user->has_order = User::HAS_ORDER;
            $user->save();
        }
    }

    protected function updateProductAndPintuanInfo($order, $orderProducts)
    {
        $rabbitMq = ToolsAbstract::getRabbitMq();

        foreach ($orderProducts as $orderProduct) {
            //更新商品销量
            $product = Product::findOne(['id' => $orderProduct['product_id']]);
            if (!empty($product)) {
                $product->sold_num += $orderProduct['number'];
                $product->save();

                //发布商品更新的消息
                $data = [
                    'route' => 'taskProduct.productUpdateProcess',
                    'params' => [
                        'product_id' => $product->id,
                    ],
                ];
                $rabbitMq->publish($data);
            }
        }
    }
}