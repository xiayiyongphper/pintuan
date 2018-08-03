<?php

namespace service\tools\pay;

use common\lib\wxpay\WxPayApi;
use common\lib\wxpay\WxPayNotify;
use common\lib\wxpay\WxPayOrderQuery;

class PayNotifyCallBack extends WxPayNotify
{
    protected $queryResult = [];
    //查询订单
    public function Queryorder($transaction_id, $out_trade_no = '')
    {
        $input = new WxPayOrderQuery();
        if ($transaction_id) {
            $input->SetTransaction_id($transaction_id);
        }
        if ($out_trade_no) {
            $input->SetOut_trade_no($out_trade_no);
        }
        $result = WxPayApi::orderQuery($input);
        $this->setQueryResult($result);
        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        $status = true;
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            $status = false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            $status = false;
        }
        $this->order_number = isset($data["out_trade_no"]) ? $data["out_trade_no"] : '';
        $this->total_fee = isset($data["total_fee"]) ? $data["total_fee"] : '';
        $this->transaction_id = isset($data["transaction_id"]) ? $data["transaction_id"] : '';
        $this->bank_type = isset($data["bank_type"]) ? $data["bank_type"] : '';
        $this->settlement_total_fee = isset($data["settlement_total_fee"]) ? $data["settlement_total_fee"] : '';

        if ($status == true) {
            $this->SetReturn_code("SUCCESS");
            $this->SetReturn_msg("OK");
        } else {
            $this->SetReturn_code("FAIL");
            $this->SetReturn_msg($msg);
        }
    }

    /**
     * @return array
     */
    public function getQueryResult(): array
    {
        return $this->queryResult;
    }

    /**
     * @param array $queryResult
     */
    public function setQueryResult($queryResult)
    {
        $this->queryResult = $queryResult;
    }

}

