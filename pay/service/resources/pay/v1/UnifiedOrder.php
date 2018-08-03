<?php

namespace service\resources\pay\v1;

use common\lib\wxpay\WxPayApi;
use common\lib\wxpay\WxPayConfig;
use common\lib\wxpay\WxPayUnifiedOrder;
use message\pay\WxUnifiedOrderRequest;
use message\pay\WxUnifiedOrderResponse;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-21
 * Time: 下午4:42
 */
class UnifiedOrder extends ResourceAbstract
{
    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param $data
     * @return mixed
     * @internal param string $bytes
     */
    public function run($data)
    {
        /** @var WxUnifiedOrderRequest $request */
        $request = $this->parseRequest($data);
        Tools::log($request->toArray(), 'UnifiedOrder.log');
        $input = new WxPayUnifiedOrder();
        $input->SetBody($request->getBody());
        $input->SetAttach($request->getAttach());
        $input->SetOut_trade_no($request->getOutTradeNo());
        $input->SetTotal_fee($request->getTotalFee());
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(WxPayConfig::NOTIFY_URL);
        $input->SetTrade_type(WxPayConfig::TRADETYPE);
        $input->SetOpenid($request->getOpenid());
        $order = WxPayApi::unifiedOrder($input);
        Tools::log($order, 'UnifiedOrder.log');
        $prepay_id = $order['prepay_id'];
        $nonceStr = strtoupper(WxPayApi::getNonceStr());
        $package = "prepay_id={$prepay_id}";
        $str = 'appId=' . WxPayConfig::APPID . '&nonceStr=' . $nonceStr . '&package=' . $package . '&signType=MD5&timeStamp=' .
            time() . '&key=' . WxPayConfig::KEY;
        $paySign = strtoupper(md5($str));

        $response = self::response();
        $response->setAppId(WxPayConfig::APPID);
        $response->setTimeStamp(time());
        $response->setNonceStr($nonceStr);
        $response->setPackage($package);
        $response->setSignType('MD5');
        $response->setPaySign($paySign);
        $response->setPrepayId($prepay_id);
//        Tools::log($response->toArray(), 'UnifiedOrder.log');
        return $response;
    }

    public static function request()
    {
        return new WxUnifiedOrderRequest();
    }

    public static function response()
    {
        return new WxUnifiedOrderResponse();
    }
}