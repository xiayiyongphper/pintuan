<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\CommissionRecord;
use common\models\Order;
use framework\components\ToolsAbstract;
use message\order\orderVerificationReq;
use message\order\orderVerificationSaveReq;
use message\order\orderVerificationSaveRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;
use yii\helpers\ArrayHelper;

/**
 * Class createOrder
 * @package service\resources\order\v1
 * 商家对订单核销保存
 */
class orderVerificationSave extends ResourceAbstract
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
        $order_id = trim($request->getOrderId());// 订单id

        // 查询该待核销订单是否存在
        /**@var Order $orderInfo * */
        $orderInfo = Order::find()->where(['id' => $order_id, 'pick_code' => $pick_code, 'store_id' => $store_id, 'status' => Order::STATUS_ARRIVED])->one();
        if (!$orderInfo) {
            Exception::throwException(Exception::STORE_PICK_CODE_ERROR);
        }

        //小店已经确认到货，才可以核销
        if ($orderInfo->status != Order::STATUS_ARRIVED) {
            Exception::throwException(Exception::STORE_ARRIVAL_ORDER_EMPTY);
        }

        // 更改订单状态
        $orderInfo->status = Order::STATUS_CONFIRMED;// 核销 已确认收货
        if (!$orderInfo->save()) {
//            Tools::log($orderInfo->errors, 'orderVerificationFail.log');
            Exception::throwException(Exception::STORE_ORDER_VERIFICATION_FAIL);
        }

        // 订单核销之后更改佣金表commission_record的数据的状态 直接转入钱包
        $commission = CommissionRecord::findOne(['order_id' => $order_id, 'store_id' => $store_id, 'del' => 1, 'status' => 2]);
        if ($commission) {
            $commission->status = 3;// 核销过后直接转入钱包
            $commission->transfer_at = date('Y-m-d H:i:s');// 核销过后直接转入钱包的时间
            if (!$commission->save()) {
//                Tools::log($commission->errors, 'commissionFail.log');
                Exception::throwException(Exception::STORE_ORDER_VERIFICATION_FAIL);
            }
            $responseData['commission'] = $commission->attributes;
        }

        $responseData['order'] = ArrayHelper::toArray($orderInfo);
//        Tools::log($responseData,'orderVerificationSaveRes.log');


        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new orderVerificationSaveReq();
    }

    public static function response()
    {
        return new orderVerificationSaveRes();
    }
}