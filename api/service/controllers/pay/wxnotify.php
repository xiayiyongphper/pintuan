<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/12
 * Time: 15:41
 */

namespace service\controllers\pay;

use framework\ApiAbstract;
use framework\Tool;
use message\common\Order;
use message\order\OrderPayRes;
use message\pay\WxNotifyOrderResponse;
use message\user\UserResponse;
use service\callService\order\OrderDetailProxy;
use service\callService\order\orderInfoProxy;
use service\callService\order\OrderPayProxy;
use service\callService\pay\WxNotifyProxy;
use service\callService\product\AddPintuanUserProxy;
use service\callService\product\PintuanChangeProxy;
use service\callService\user\UserDetailProxy;

/**
 * Class test
 */
class wxnotify extends ApiAbstract
{

    public function run($params)
    {
        $request = [
            'data' => $params,
        ];
        //检测订单金额是否正确,通知微信收到回调
        /** @var WxNotifyOrderResponse $result */
        $result = (new WxNotifyProxy('pay', 'pay.NotifyOrder', $request))->sendRequest();

        $request = [
            'order_number' => $result->getOrderId()
        ];

        //获取订单和商品信息
        /** @var Order $result_order */
        $result_order = (new orderInfoProxy($request))->sendRequest();

        try {
            //开团的单改为有效团
            if ($result_order->getType() == 3) {
                (new PintuanChangeProxy([
                    'pintuan_id' => $result_order->getPintuanId(),
                ]))->sendRequest();
            }

            //拼团参与人增加
            if ($result_order->getType() == 2) {
                /** @var UserResponse $userInfo */
                $userInfo = (new UserDetailProxy(['user_id' => $result_order->getUserId()]))->sendRequest();
                $addPIntuanUserParams = [
                    'user_id' => $userInfo->getUserId(),
                    'nick_name' => $userInfo->getNickName(),
                    'avatar_url' => $userInfo->getAvatarUrl(),
                    'pintuan_id' => $result_order->getPintuanId()
                ];
                //Tool::log($addPIntuanUserParams,'wx_notify.log');
                (new AddPintuanUserProxy($addPIntuanUserParams))->sendRequest();
            }
        } catch (\Exception $e) {
            Tool::logException($e);
        } catch (\Error $e) {
            Tool::logError($e);
        }


        //修改订单状态为已已支付
        (new OrderPayProxy([
            'order_number' => $result->getOrderId(),
            'pay_amount' => $result->getTotalFee(),
            'bank_type' => $result->getBankType(),
            'settlement_total_fee' => $result->getSettlementTotalFee(),
            'transaction_id' => $result->getTransactionId(),
        ]))->sendRequest();

        return $result->getXmlResponse();
    }

    protected function getRules()
    {
        // TODO: Implement getRules() method.
    }
}