<?php

namespace service\resources\pay\v1;

use framework\components\ToolsAbstract;
use message\pay\WxNotifyOrderRequest;
use message\pay\WxNotifyOrderResponse;
use message\pay\WxQueryOrderRequest;
use message\pay\WxQueryOrderResponse;
use service\resources\ResourceAbstract;
use service\tools\pay\PayNotifyCallBack;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-21
 * Time: 下午4:42
 */
class QueryOrder extends ResourceAbstract
{
    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param $data
     * @internal param string $bytes
     * @return WxNotifyOrderResponse|mixed
     */
    public function run($data)
    {
        /** @var WxQueryOrderRequest $request */
        $request = $this->parseRequest($data);
        $response = self::response();

        $order_number = $request->getOrderNumber();
        ToolsAbstract::log($order_number, 'QueryOrder.log');
        $notify = new PayNotifyCallBack();
        $flag = $notify->Queryorder('', $order_number);
        $queryResult = $notify->getQueryResult();
        $returnCode = isset($queryResult['return_code']) ? $queryResult['return_code'] : 'FAIL';

        $response->setStatus($returnCode);

        if ($returnCode === 'FAIL' || !$flag) {
            return $response;
        }

        $order_number = isset($queryResult['out_trade_no']) ? $queryResult['out_trade_no'] : '';
        $total_fee = isset($queryResult['total_fee']) ? $queryResult['total_fee'] : '';
        $return_msg = isset($queryResult['return_msg']) ? $queryResult['return_msg'] : '';
        $bank_type = isset($queryResult['bank_type']) ? $queryResult['bank_type'] : '';
        $settlement_total_fee = isset($queryResult['settlement_total_fee']) ? $queryResult['settlement_total_fee'] : '';
        $transaction_id = isset($queryResult['transaction_id']) ? $queryResult['transaction_id'] : '';

        $response->setOrderNumber($order_number);
        $response->setTotalFee($total_fee);
        $response->setMsg($return_msg);
        $response->setBankType($bank_type);
        $response->setSettlementTotalFee($settlement_total_fee);
        $response->setTransactionId($transaction_id);
        ToolsAbstract::log($response->toArray(), 'NotifyOrder.log');
        return $response;
    }

    public static function request()
    {
        return new WxQueryOrderRequest();
    }

    public static function response()
    {
        return new WxQueryOrderResponse();
    }
}