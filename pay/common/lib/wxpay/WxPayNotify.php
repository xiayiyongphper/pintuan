<?php

namespace common\lib\wxpay;

/**
 *
 * 回调基础类
 * @author widyhu
 *
 */
class WxPayNotify extends WxPayNotifyReply
{
    public $order_number = '';
    public $total_fee = 0;
    public $bank_type = '';
    public $settlement_total_fee = 0;
    public $transaction_id = '';

    public $xml_response = '';
    /*×
     * 回调入口
     * @param bool $needSign 是否需要签名输出
     */
    final public function Handle($needSign = true, $data)
    {
        //当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
        WxPayApi::notify(array($this, 'NotifyCallBack'), $msg, $data);
        $this->xml_response = $this->ReplyNotify($needSign);
        return $this;
    }

    /**
     *
     * 回调方法入口，子类可重写该方法
     * 注意：
     * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
     * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
     * @param array $data 回调解释出的参数
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data, &$msg)
    {
        //TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
        return true;
    }

    /**
     *
     * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
     * @param array $data
     * true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    final public function NotifyCallBack($data)
    {
        $this->NotifyProcess($data, $msg);
    }

    /**
     *
     * 回复通知
     * @param bool $needSign 是否需要签名输出
     */
    final private function ReplyNotify($needSign = true)
    {
        //如果需要签名
        if ($needSign == true &&
            $this->GetReturn_code() == "SUCCESS") {
            $this->SetSign();
        }
//        WxPayApi::replyNotify($this->ToXml());
        return $this->ToXml();
    }
}