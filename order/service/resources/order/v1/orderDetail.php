<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use common\models\Order;
use common\models\OrderAddress;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\order\OrderAction;
use message\order\OrderDetailRequest;
use message\order\OrderDetailResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;

/**
 * Author: wjq310
 * Class orderDetail
 * @package service\resources\order\v1
 */
class orderDetail extends ResourceAbstract
{
    const WAIT_SHARE = 51; // 待分享状态

    public function run($data)
    {
        $this->doInit($data);
        /** @var OrderAction $request */
        $request = $this->request;

        if (!$request->getUserId() || !$request->getOrderId()) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        $order_id = $request->getOrderId();  // 订单id
        $user_id = $request->getUserId();  // 用户id

        /** @var Order $order */
        $order = Order::find()->where(['id' => $order_id, 'user_id' => $user_id])->asArray()->one();
        if (empty($order)) {
            Exception::throwException(Exception::ORDER_NOT_EXIST);
        }
//        ToolsAbstract::log("order: " . var_export($order, true), "wjqOrderTest.log");
        $data = [];
        $data['order_info']['id'] = $order['id'];
        // 待分享状态处理
        if ($order['status'] == 2 && $order['enable_deliver_time'] == '0000-00-00 00:00:00') {
            $data['order_info']['status'] = self::WAIT_SHARE;
        } else {
            $data['order_info']['status'] = $order['status'];
        }
        $data['order_info']['real_amount'] = $order['real_amount'] / 100;
        $data['order_info']['payable_amount'] = $order['payable_amount'] / 100;
        $data['order_info']['order_number'] = $order['order_number'];
        $data['order_info']['create_at'] = $order['create_at'];
        $data['order_info']['enable_deliver_time'] = $order['enable_deliver_time'];
        $data['order_info']['pintuan_activity_id'] = $order['pintuan_activity_id'];
        $data['order_info']['type'] = isset($order['type']) ? $order['type'] : 1;
        $data['order_info']['pick_code'] = isset($order['pick_code']) ? $order['pick_code'] : "000000";
        $data['order_info']['store_name'] = isset($order['store_name']) ? $order['store_name'] : "";
        $data['order_info']['address_nick_name'] = "";
        $data['order_info']['address_phone'] = "";
        $data['order_info']['address'] = "";
        $order_type = $order['type']; // 订单类型：1-普通购买，2-参与拼团，3-发起拼团

        //优惠券信息
        $data['order_info']['coupon_id'] = $order['coupon_id'];
        $data['order_info']['discount_amount'] = !empty($order['discount_amount']) ? (string)round($order['discount_amount'] / 100, 2) : "0";

        $order_address = OrderAddress::find()->select(['name', 'phone', 'address'])->where(["order_id" => $order_id])->asArray()->one();
        if (!empty($order_address)) {
            $data['order_info']['address_nick_name'] = $order_address['name'];
            $data['order_info']['address_phone'] = $order_address['phone'];
            $data['order_info']['address'] = $order_address['address'];
        }

        if ($order_type == 1) { // 普通购买的，没有 pintuan_info 和 pintuan_user
            $data['order_info']['pintuan_info'] = [];
            $data['order_info']['pintuan_user'] = [];
        }

        $data['order_info']['order_product'] = [];
        // 由order_id 从 order_product 拿到 pintuan_id
        $order_product = OrderProduct::find()->select(['product_id', 'pintuan_id', 'specification_id', 'number', 'name', 'images', 'price', 'deal_price', 'item_detail'])->where(["order_id" => $order_id])->asArray()->all();
        if (!empty($order_product)) {
            foreach ($order_product as $key => $val) {
                $data['order_info']['order_product'][$key]['product_id'] = $val['product_id'];
                $pintuan_id = $data['order_info']['order_product'][$key]['pintuan_id'] = $val['pintuan_id'];
                $data['order_info']['order_product'][$key]['specification_id'] = $val['specification_id'];
                $data['order_info']['order_product'][$key]['number'] = $val['number'];
                $data['order_info']['order_product'][$key]['name'] = $val['name'];
                $data['order_info']['order_product'][$key]['images'] = explode(";", $val['images']);
                $data['order_info']['order_product'][$key]['price'] = !empty($val['price']) ? (string)round($val['price'] / 100, 2) : "0";
                $data['order_info']['order_product'][$key]['deal_price'] = !empty($val['deal_price']) ? (string)round($val['deal_price'] / 100, 2) : "0";
                $data['order_info']['order_product'][$key]['item_detail'] = "";
                if (!empty($val['item_detail'])) {
                    $res_tmp = json_decode($val['item_detail'], true);
                    $item_detail = "";
                    if (!empty($res_tmp)) {
                        foreach ($res_tmp as $v) {
                            $item_detail .= $v . ",";
                        }
                    }
                    $data['order_info']['order_product'][$key]['item_detail'] = trim($item_detail, ",");
                }

            }
        }
        $data['order_info']['pintuan_id'] = isset($pintuan_id) ? $pintuan_id : 0;
//        ToolsAbstract::log("data: " . var_export($data, true), "wjqOrderTest.log");
        $response = self::response();
        $response->setFrom(ToolsAbstract::pb_array_filter($data));
        return $response;
    }

    public static function request()
    {
        return new OrderDetailRequest();
    }

    public static function response()
    {
        return new OrderDetailResponse();
    }

}
