<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\Order;
use common\models\OrderAddress;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\order\orderVerificationReq;
use message\order\orderVerificationRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class createOrder
 * @package service\resources\order\v1
 * 商家对订单核销
 */
class orderVerification extends ResourceAbstract
{
    public function run($data)
    {
        /** @var orderVerificationReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 查询今天以前所有未到货的订单
        $pick_code = trim($request->getPickCode());// 提货码
        $store_id = trim($request->getStoreId());// 自提点id

        // 查询该待核销订单是否存在
        $orderInfo = order::find()->select('id,order_number,status,amount,pick_code,create_at')
            ->where(['pick_code' => $pick_code, 'store_id' => $store_id])
            ->asArray()->one();
        if (!$orderInfo) {
            Exception::throwException(Exception::STORE_PICK_CODE_ERROR);
        }

        if ($orderInfo['status'] != Order::STATUS_ARRIVED && $orderInfo['status'] != Order::STATUS_CONFIRMED) {
            Exception::throwException(Exception::STORE_NO_ARRIVAL);
        }

        $orderInfo['amount'] = $orderInfo['amount'] / 100;

        // 订单存在 则返回信息
        $responseData['order'] = $orderInfo;

        // 查询出订单用户信息
        $userInfo = OrderAddress::findOne(['order_id' => $orderInfo['id']]);
        $responseData['order']['name'] = $userInfo ? $userInfo->name : '';
        $responseData['order']['phone'] = $userInfo ? $userInfo->phone : '';

//        Tools::log($orderInfo, 'orderVerification.log');
        // 查询出订单商品信息
        $orderProducts = OrderProduct::find()->where(['order_id' => $orderInfo['id']])->all();
        if (empty($orderProducts)) {
            Exception::throwException(Exception::STORE_ORDER_PRODUCT_EMPTY);
        }
        $orderProductItems = [];
        /** @var OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            $orderProductItem['images'] = explode(';', $orderProduct->images);
            $orderProductItem['item_detail'] = implode(',', json_decode($orderProduct->item_detail, true));
            $orderProductItem['number'] = $orderProduct->number;
            $orderProductItem['name'] = $orderProduct->name;
            $orderProductItem['product_id'] = $orderProduct->product_id;
            $orderProductItem['deal_price'] = $orderProduct->deal_price / 100;
            $orderProductItems[] = $orderProductItem;
        }

        $responseData['order_product'] = $orderProductItems;

//        Tools::log($responseData, 'orderVerification.log');
        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new orderVerificationReq();
    }

    public static function response()
    {
        return new orderVerificationRes();
    }
}