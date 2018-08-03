<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 15:02
 * 微信小程序模板消息发送类
 */

namespace common\helper;
use framework\components\ToolsAbstract;

class WxMiniProgramTemplateMsg
{
    // 获取token
    const GETTOKEN = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential";
    // 小程序模板发送
    const WXOPENSENDMSG = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=";
    // 小程序订单发货提醒
    const ORDERDELIVERNOTIFYTEMPLATEID = "7vn7VshTvokQ3KU5fo0GgXlxOWWtDPZs9xMVX16ozg4";
    // 小程序订单到货领取通知
    const ORDERARRIVALNOTIFYTEMPLATEID = "uxp-Hj5Y5ZZiul9D__2mCwQ63RsdCHiBT8WkQwHIkzc";
    // 微信小程序模板文字显示颜色
    const WXCOLOR = "#173177";
    // 小程序订单发货提醒的温馨提示
    const ORDERDELIVERNOTIFYCOMMENT = "您购买的商品已发货，我们会尽快送到自提点，请留意到货通知！";
    // 小程序订单到货通知的温馨提示
    const ORDERARRIVALNOTIFYCOMMENT = "您购买的商品已到货，请您尽快到自提点取货";

    const TOKEN = "11_GhSRujf8ssLWCapoCK1uaJnyQrmhbGdG3uq5yi6KPDuTURWip48piEE2HvXXrMG02w-WChH2t44KL5P8_R9ccofotzXsLB0z4nDCN-C5mvKuYlV0RjM9nPHFj3zKp8lIaaqc_2-bOH8SBhh9ZSVaAFADWZ";

    private $appid = "wxe1e1e9377ce16ec3";
    private $appsecret = "e21278de667867dd0339d22c9afb770e";
    private $access_token = "";

    public function __construct($appid="",$appsecret="")
    {
        if(!empty($appid) && !empty($appsecret)){
            $this->appid = $appid;
            $this->appsecret = $appsecret;
        }
        $this->getAccessToken();
//        $this->access_token = self::TOKEN;
    }

    public function getAccessToken()
    {
        $redis = ToolsAbstract::getRedis();
        $this->access_token = $redis->get("wx_mini_program_access_token");
        if(empty($this->access_token)){
            $url = self::GETTOKEN."&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->http_get($url);
            $res = json_decode($res,true);
            if(empty($res['access_token'])){
                ToolsAbstract::log(__CLASS__."--".__METHOD__." -- get access_token failed ".var_export($res,true),"weixintemplate.log");
                return false;
            }
            $redis->set("wx_mini_program_access_token",$res['access_token'],7100); // 7100秒
            $this->access_token = $res['access_token'];
        }
        return $this->access_token;
    }

/*    public function getAccessToken()
    {
        $url = self::GETTOKEN."&appid=".$this->appid."&secret=".$this->appsecret;
        $res = $this->http_get($url);
        $res = json_decode($res,true);
        if(empty($res['access_token'])){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." -- get access_token failed ".var_export($res,true),"weixintemplate.log");
            return false;
        }
        $this->access_token = $res['access_token'];
//        var_dump($this->access_token);exit;
    }*/

    /**
     * 小程序订单发货通知的消息
     * @param string $open_id  小程序用户的open_id
     * @param string $prepay_id  订单微信返回的prepay_id
     * @param string $order_id  订单id
     * @param string $product_name  商品名称
     * @param string $product_order_amount  商品订单金额 为元
     */
    public function sendOrderDeliverNotify($data){
        if(empty($data)){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." -- data: is null","weixintemplate.log");
            return false;
        }
        if(empty($data['open_id']) || empty($data['prepay_id'])  || empty($data['order_id']) || empty($data['product_name']) || empty($data['product_order_amount'])){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." -- data: ".json_encode($data)." -- 有参数为空","weixintemplate.log");
            return false;
        }
        $touser = $data['open_id']; // oGczD5IKuvDbYycnopd3TEiOL0iQ
        $prepay_id = $data['prepay_id'];
        $order_id = $data['order_id'];
        $product_name = $data['product_name'];
        $product_order_amount = "￥".(string)($data['product_order_amount']);

        $url = self::WXOPENSENDMSG.$this->access_token;
        $params = [
            "touser" => $touser,
            "template_id" => self::ORDERDELIVERNOTIFYTEMPLATEID,
            "page" => "pages/orderdetail/orderdetail?id=".$order_id,
//            "page" => "pages/pintuandetail/pintuandetail?id=54",  // 待修改
            "form_id" => $prepay_id,
            "data" => [
                "keyword1"=>["value"=>$product_name,"color"=>self::WXCOLOR],
                "keyword2"=>["value"=>$product_order_amount,"color"=>self::WXCOLOR],
                "keyword3"=>["value"=>self::ORDERDELIVERNOTIFYCOMMENT,"color"=>self::WXCOLOR],
            ]
        ];
        $res = $this->http_post($url,$params);
        $res = json_decode($res,true);
        if($res['errcode'] !== 0){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." url: ".$url." -- data: ".json_encode($params),"weixintemplate.log");
            ToolsAbstract::log(__CLASS__."--".__METHOD__." wx_return : ".var_export($res,true),"weixintemplate.log");
            return $res;
        }
        return $res;
    }

    /**
     * 小程序订单到货领取通知
     * @param string $prepay_id  订单微信返回的prepay_id
     * @param string $code  提货码
     * @param string $product_arrival_date 到货时间
     * @param string $product_name 商品名称
     * @param int $product_number 商品数量
     * @param string $product_order_amount 订单金额 为元
     */
    public function sendOrderArrivalNotify($data)
    {
        if(empty($data)){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." -- data: is null","weixintemplate.log");
            return false;
        }
        if(empty($data['open_id']) || empty($data['order_id']) || empty($data['prepay_id']) || empty($data['code'])  || empty($data['product_name']) || empty($data['product_order_amount'])){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." -- data: ".json_encode($data)." -- 有参数为空","weixintemplate.log");
            return false;
        }
        if(empty($data['product_number'])){
            $data['product_number'] = "1";
        }

        $touser = $data['open_id']; // oGczD5IKuvDbYycnopd3TEiOL0iQ
        $order_id = $data['order_id'];
        $prepay_id = $data['prepay_id'];
        $order_content = $data['product_name']."*".$data['product_number']; // 订单内容为 商品名称
        $code = $data['code'];
        $product_order_amount = "￥".(string)($data['product_order_amount']);

        $url = self::WXOPENSENDMSG.$this->access_token;
        $params = [
            "touser" => $touser,
            "template_id" => self::ORDERARRIVALNOTIFYTEMPLATEID,
            "page" => "pages/orderdetail/orderdetail?id=".$order_id,
//            "page" => "pages/pintuandetail/pintuandetail?id=54",  // 待修改
            "form_id" => $prepay_id,
            "data" => [
                "keyword1"=>["value"=>$code,"color"=>self::WXCOLOR],
                "keyword2"=>["value"=>$order_content,"color"=>self::WXCOLOR],
                "keyword3"=>["value"=>$product_order_amount,"color"=>self::WXCOLOR],
                "keyword4"=>["value"=>self::ORDERARRIVALNOTIFYCOMMENT,"color"=>self::WXCOLOR],
            ],
            "emphasis_keyword"=>"keyword1.DATA"
        ];
        $res = $this->http_post($url,$params);
        $res = json_decode($res,true);
        if($res['errcode'] !== 0){
            ToolsAbstract::log(__CLASS__."--".__METHOD__." url: ".$url." -- data: ".json_encode($params),"weixintemplate.log");
            ToolsAbstract::log(__CLASS__."--".__METHOD__." wx_return : ".var_export($res,true),"weixintemplate.log");
            return $res;
        }
        return $res;
    }


    /**
     * GET 请求
     * @param string $url
     */
    private function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus  = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }


    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url, $param, $post_file = false) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $strPOST = json_encode($param);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus  = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }


}