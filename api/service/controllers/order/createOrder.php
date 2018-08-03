<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\order;

use framework\ApiAbstract;
use framework\Exception;
use framework\Tool;
use framework\validParam;
use message\common\Order;
use message\order\OrderPayRes;
use message\pay\WxUnifiedOrderResponse;
use message\product\Pintuan;
use message\product\PintuanStartRes;
use message\store\Store;
use message\user\UserResponse;
use service\callService\order\CreateOrderProxy;
use service\callService\order\orderInfoProxy;
use service\callService\order\OrderPayProxy;
use service\callService\order\OrderUpdateProxy;
use service\callService\pay\PayProxy;
use service\callService\product\AddPintuanUserProxy;
use service\callService\product\GetRawProductProxy;
use service\callService\product\PintuanChangeProxy;
use service\callService\product\PintuanStartProxy;
use service\callService\store\GetStoreDetailProxy;
use service\callService\store\GetWholesalerDistrictListProxy;
use service\callService\user\UserDetailProxy;

/**
 * Class createOrder
 */
class createOrder extends ApiAbstract
{
    const TYPE_NORMAL = 1;//普通购买
    const TYPE_JOIN_PINTUAN = 2;//参与拼团
    const TYPE_LAUNCH_PINTUAN = 3;//发起拼团

    public function run($params)
    {
        $this->doInit($params);
        if (!in_array($this->_request['type'], [self::TYPE_NORMAL, self::TYPE_JOIN_PINTUAN, self::TYPE_LAUNCH_PINTUAN])) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        Tool::log($this->_request, 'createOrder.log');
        foreach ($this->_request['products'] as $proItem) {
            if ($this->_request['type'] == self::TYPE_JOIN_PINTUAN) {
                if (empty($proItem['pintuan_id'])) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }
            } elseif ($this->_request['type'] == self::TYPE_LAUNCH_PINTUAN) {
                if (empty($proItem['pintuan_activity_id'])) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }
            }
        }

        $this->_result['type'] = $this->_request['type'];
        $storeId = $this->_request['store_id'];

        //获取自提点信息
        /** @var Store $store */
        $store = (new GetStoreDetailProxy(['store_id' => $storeId]))->sendRequest();
        $store_name = $store->getStoreName();

        //获取商品信息
        foreach ($this->_request['products'] as $item) {
            if (empty($item['specification_id']) && empty($item['pintuan_id']) && empty($item['pintuan_activity_id'])) {
                Exception::throwException(Exception::SPECIFICATION_PINTUAN_NEED_ONE);
            }
        }

        $activityId = $this->getNewUserActivity();//新人活动ID
        $productParams = [
            'type' => $this->_request['type'],
            'items' => $this->_request['products'],
            'wholesaler_ids' => $this->_wholesalerIds,
            'store_id' => $storeId,
            'activity_id' => $activityId,
        ];
        $products = (new GetRawProductProxy($productParams))->sendRequest();
        $products = $products->toArray();
        $include_new_user_product = $products['include_new_user_product'] ?: 0;
        $products = $products['items'];
        $callback_pintuan_id = 0;
        foreach ($products as &$product) {
            $pintuan_activity_id = !empty($product['pintuan_activity_id']) ? $product['pintuan_activity_id'] : 0;
            if ($this->_request['type'] == 3 && $pintuan_activity_id > 0) {
                $pintuanStartParams = [
                    'nick_name' => $this->_user->getNickName(),
                    'avatar_url' => $this->_user->getAvatarUrl(),
                    'user_id' => $this->_user->getUserId(),
                    'pintuan_activity_id' => $pintuan_activity_id
                ];
                /** @var Pintuan $pintuan */
                $pintuan = (new PintuanStartProxy($pintuanStartParams))->sendRequest();
                $pintuan_id = $pintuan->getId();
                $callback_pintuan_id = $pintuan_id;
                $product['pintuan_id'] = $pintuan_id;
            }
        }

        $createOrderParams = [
            'user_id' => $this->_user->getUserId(),
            'store_id' => $storeId,
            'receiver_name' => $this->_request['receiver_name'],
            'receiver_phone' => $this->_request['receiver_phone'],
            'address' => $this->_request['address'],
            'store_name' => $store_name,
            'type' => $this->_request['type'],
            'items' => $products,
            'coupon_id' => isset($this->_request['coupon_id']) ? $this->_request['coupon_id'] : 0,
            'include_new_user_product' => $include_new_user_product,
        ];
        $res = (new CreateOrderProxy($createOrderParams))->sendRequest()->toArray();

        //支付金额为0时，逻辑特殊处理，微信不会回调
        if (0 == $res['payable_amount']) {

            $request = [
                'order_id' => $res['order_id']
            ];

            //获取订单和商品信息
            /** @var Order $result_order */
            $result_order = (new orderInfoProxy($request))->sendRequest();

            //开团的单改为有效团
            if ($this->_request['type'] == 3) {
                (new PintuanChangeProxy([
                    'pintuan_id' => $result_order->getPintuanId(),
                ]))->sendRequest();
            }

            //拼团参与人增加
            if ($this->_request['type'] == 2) {
                $addPIntuanUserParams = [
                    'user_id' => $this->_user->getUserId(),
                    'nick_name' => $this->_user->getNickName(),
                    'avatar_url' => $this->_user->getAvatarUrl(),
                    'pintuan_id' => $result_order->getPintuanId(),
                ];
                //Tool::log($addPIntuanUserParams,'wx_notify.log');
                (new AddPintuanUserProxy($addPIntuanUserParams))->sendRequest();
            }

            //修改订单状态为已已支付
            /** @var OrderPayRes $orderPayRes */
            $orderPayRes = (new OrderPayProxy([
                'order_number' => $res['order_number'],
                'pay_amount' => $res['payable_amount'],
            ]))->sendRequest();

            $result['order_number'] = $orderPayRes->getOrderNumber();
            $result['order_id'] = $res['order_id'];
            $result['pintuan_id'] = $orderPayRes->getPintuanId();
            return $result;
        }


        //调用支付
        $proNames = [];
        foreach ($products as $product) {
            $proNames[] = $product['name'];
        }
        $request = [
            'body' => "拼团商品",
            'detail' => implode(';', $proNames),
            'out_trade_no' => $res['order_number'],
            'total_fee' => $res['payable_amount'],
            'openid' => $this->_user->getOpenId(),
        ];
        Tool::log($request, 'createOrder.log');
        /** @var WxUnifiedOrderResponse $result */
        $result = (new PayProxy($request))->sendRequest();

        $prepay_id = $result->getPrepayId();

        $orderUpdateRequest = [
            'order_number' => $res['order_number'],
            'prepay_id' => $prepay_id
        ];

        //更新订单中的prepay_id，用于发送消息给用户
        (new OrderUpdateProxy($orderUpdateRequest))->sendRequest();

        $this->_result = $result->toArray();
        $this->_result['pintuan_id'] = $callback_pintuan_id;
        Tool::log($this->_result, 'createOrder.log');
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['type', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['receiver_name', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['receiver_phone', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['address', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['coupon_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['products', validParam::CHECK_TYPE_REPEATED_REQUIRE, 'item'],
            ],
            'item' => [
                ['product_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['pintuan_activity_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['specification_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['pintuan_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['product_num', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ]
        ];
    }
}