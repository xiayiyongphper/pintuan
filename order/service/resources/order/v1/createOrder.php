<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\order\v1;

use common\models\Order;
use common\models\OrderAddress;
use common\models\OrderProduct;
use common\models\SalesRuleUserCoupon;
use framework\components\ToolsAbstract;
use message\common\BuyItem;
use message\order\CreateOrderReq;
use message\order\CreateOrderRes;
use service\helpers\UniqueOrderId;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\quote\Item;
use service\tools\quote\Quote;
use service\tools\Tools;

/**
 * Class createOrder
 * @package service\resources\order\v1
 */
class createOrder extends ResourceAbstract
{
    /** @var  CreateOrderReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        if (!$this->request->getItemsCount()) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        $user_id = $this->request->getUserId();
        $coupon_id = $this->request->getCouponId() ?: 0;

        $include_new_user_product = 0;
        foreach ($this->request->getItems() as $product) {
            if($product->getNewUserPrice()){
                $include_new_user_product = 1;
                break;
            }
        }

        $quote = new Quote();
        $quote->setUserId($user_id);
        $quote->setCouponId($coupon_id);
        $quote->setIncludeNewUserProduct($include_new_user_product);

        /** @var BuyItem $product */
        foreach ($this->request->getItems() as $product) {
            $item = new Item();
            $item->setDealPrice($product->getDealPrice());
            $item->setNumber($product->getProductNum());
            $quote->addItem($item);
        }

        $quote->init()->collectTotals();

        $transaction = Order::getDb()->beginTransaction();
        try {
            $order = new Order();
            $order->order_number = (new UniqueOrderId(1, self::getWorkerId()))->nextId();
            $order->user_id = $this->request->getUserId();
            $order->store_id = $this->request->getStoreId();
            $order->pay_type = Order::PAY_TYPE_WECHAT;
            $order->status = Order::STATUS_UNPAID;
            $order->refund_status = Order::REFUND_STATUS_UNREFUND;
            $order->receive_type = Order::REVEIVE_TYPE_NOT_REVEIVED;
            $order->type = $this->request->getType();
            $order->store_name = $this->request->getStoreName();//王洋这个坑货不听劝，非要加
            $order->pick_code = $this->getPickCode();
            $order->coupon_id = $this->request->getCouponId();

            $orderProductArr = [];
            $revertQtyParms = [];
            /** @var  $item */
            foreach ($this->request->getItems() as $item) {
                $orderProduct = new OrderProduct();
                $orderProduct->product_id = $item->getProductId();
                $orderProduct->pintuan_id = $item->getPintuanId() ?: 0;
                $orderProduct->buy_chains_id = $item->getBuyChainsId() ?: 0;
                $orderProduct->specification_id = $item->getSpecificationId();
                $orderProduct->number = $item->getProductNum();
                $orderProduct->deal_price = $item->getDealPrice();
                $orderProduct->name = $item->getName();
                $orderProduct->wholesaler_id = $item->getWholesalerId();
                $orderProduct->images = $item->getImages();
                $orderProduct->description = $item->getDescription();
                $orderProduct->third_category_id = $item->getThirdCategoryId();
                $orderProduct->item_detail = $item->getItemDetail();
                $orderProduct->purchase_price = $item->getPurchasePrice();
                $orderProduct->pick_commission = $item->getPickCommission();
                $orderProduct->promote_commission = $item->getPromoteCommission() ?: 0;
                $orderProduct->price = $item->getPrice();
                $orderProduct->pintuan_price = $item->getPintuanPrice() ?: 0;
                $orderProduct->new_user_price = $item->getNewUserPrice() ?: 0;
                $orderProduct->deal_price = $item->getDealPrice() ?: 0;
                $order->pintuan_activity_id = $item->getPintuanActivityId() ?: 0;
                $order->pintuan_id = $item->getPintuanId() ?: 0;
                $order->buy_chains_id = $item->getBuyChainsId() ?: 0;

                $orderProductArr[] = $orderProduct;

                $revertQtyParms[] = [
                    'product_id' => $item->getProductId(),
                    'number' => $item->getProductNum(),
                ];
            }

            $order->amount = $quote->getAmount();
            $order->discount_amount = $quote->getDiscountAmount();
            $order->payable_amount = $quote->getPayableAmount();
            $order->coupon_id = $coupon_id;

            if (!$order->save()) {
                Tools::logException(new \Exception(json_encode($order->errors)));
                Exception::throwException(Exception::CREATE_ORDER_FAILED);
            }
            if (!$order->id) {
                Exception::throwException(Exception::CREATE_ORDER_FAILED);
            }

            /** @var OrderProduct $orderProduct */
            foreach ($orderProductArr as $orderProduct) {
                $orderProduct->order_id = $order->id;
                if (!$orderProduct->save() || !$orderProduct->id) {
                    Tools::logException(new \Exception(json_encode($orderProduct->errors)));
                    Exception::throwException(Exception::CREATE_ORDER_FAILED);
                }
            }

            $coupon = SalesRuleUserCoupon::findOne(['id' => $coupon_id]);
            if ($coupon) {
                //使用优惠券
                $coupon->state = SalesRuleUserCoupon::USER_COUPON_USED;
                $coupon->used_at = Tools::getDate();
                if (!$coupon->save()) {
                    Tools::logException(new \Exception(json_encode($coupon->errors)));
                    Exception::throwException(Exception::CREATE_ORDER_FAILED);
                }
            }

            $orderAddress = new OrderAddress();
            $orderAddress->order_id = $order->id;
            $orderAddress->name = $this->request->getReceiverName();
            $orderAddress->phone = $this->request->getReceiverPhone();
            $orderAddress->address = $this->request->getAddress();
            if (!$orderAddress->save() || !$orderAddress->id) {
                Tools::logException(new \Exception(json_encode($orderAddress->errors)));
                Exception::throwException(Exception::CREATE_ORDER_FAILED);
            }
        } catch (\Exception $e) {
            Tools::logException($e);
            $transaction->rollBack();
            throw $e;
        } catch (\Error $e) {
            Tools::logError($e);
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();

        //下单消息
        $mqData = [
            'order' => $order->toArray(),
            'order_product' => []
        ];
        /** @var OrderProduct $orderProduct */
        foreach ($orderProductArr as $orderProduct) {
            $mqData['order_product'][] = $orderProduct->toArray();
        }
        (ToolsAbstract::getRabbitMq())->publish([
            'route' => 'taskOrder.orderCreateProcess',
            'params' => $mqData
        ]);

        $result = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payable_amount' => $order->payable_amount,
            'pintuan_id' => $order->pintuan_id,
            'type' => $order->type,
        ];
        $this->response->setFrom($result);
        return $this->response;
    }

    /**
     * 生成提货码
     * @return string
     */
    protected function getPickCode()
    {
        $pickCode = strval(rand(100000, 999999));
        $repeatPickCodeOrder = Order::findOne([
            'pick_code' => $pickCode,
            'store_id' => $this->request->getStoreId(),
            'status' => [Order::STATUS_UNPAID, Order::STATUS_UNPAID, Order::STATUS_DELIVERED, Order::STATUS_ARRIVED]
        ]);
        if ($repeatPickCodeOrder) {
            return $this->getPickCode();
        }

        return $pickCode;
    }

    public static function request()
    {
        return new CreateOrderReq();
    }

    public static function response()
    {
        return new CreateOrderRes();
    }
}