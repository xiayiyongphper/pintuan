<?php

namespace service\resources\pay\v1;

use framework\components\ToolsAbstract;
use message\pay\WxNotifyOrderRequest;
use message\pay\WxNotifyOrderResponse;
use service\resources\ResourceAbstract;
use service\tools\pay\PayNotifyCallBack;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-21
 * Time: 下午4:42
 */
class NotifyOrder extends ResourceAbstract
{
    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param $data
     * @internal param string $bytes
     * @return WxNotifyOrderResponse|mixed
     */
    public function run($data)
    {
        /** @var WxNotifyOrderRequest $request */
        $request = $this->parseRequest($data);
        $data = $request->getData();
        ToolsAbstract::log($data, 'NotifyOrder.log');
        $notify = new PayNotifyCallBack();
        $notifyResult = $notify->Handle(false, $data);
        $response = self::response();
        $response->setStatus($notifyResult->GetReturn_code());
        $response->setOrderNumber($notifyResult->order_number);
        $response->setTotalFee($notifyResult->total_fee);
        $response->setMsg($notifyResult->GetReturn_msg());
        $response->setXmlResponse($notifyResult->xml_response);
        $response->setBankType($notifyResult->bank_type);
        $response->setSettlementTotalFee($notifyResult->settlement_total_fee);
        $response->setTransactionId($notifyResult->transaction_id);
        ToolsAbstract::log($response->toArray(), 'NotifyOrder.log');
        return $response;
    }

    public static function request()
    {
        return new WxNotifyOrderRequest();
    }

    public static function response()
    {
        return new WxNotifyOrderResponse();
    }
}