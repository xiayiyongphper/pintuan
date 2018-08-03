<?php
/**
 * 微信小程序的api
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 14:55
 */

namespace common\tools;
use common\tools\Tools;


class WeChatBaseApi
{
    //api 的token
    public static $token = '';

    //小程序配置
    const APP_ID = 'wxe1e1e9377ce16ec3';
    const SECRET = 'e21278de667867dd0339d22c9afb770e';

    /**
     *  刷新token
     * @return string
     */
    public static function fetchAccessToken()
    {
        $redis = Tools::getServerRedis();
        $access_token = $redis->get("wx_mini_program_access_token");
        if(empty($access_token)){
            $res = self::getAccessToken();
            self::$token = isset($res['access_token'])? $res['access_token'] : '';
            $redis->set("wx_mini_program_access_token",$res['access_token'],7100); // 7100秒
        } else {
            self::$token = $access_token;
        }

        return self::$token;
    }

    /**
     * 获取小程序码
     * 官方文档 https://developers.weixin.qq.com/miniprogram/dev/api/qrcode.html
     */
    public static function getWxacode($scene='', $page='', $with=640)
    {
        //接口A https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN
        //接口B https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN
        $access_token = self::fetchAccessToken();
        $url          = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token;
        $line_color   = ['r' => '0', 'g' => '0', 'b' => '0'];
        //scene 最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
        $post = [
            'scene'      => $scene,
            'page'       => $page,
            'width'      => $with,
            'auto_color' => false,
            'line_color' => (object)$line_color,
        ];
        $post = json_encode($post);

        //返回格式待联调图片数据流
        return self::postCurls($url, $post);
    }

    /**
     * POST请求https接口返回内容
     * @param  string $url  [请求的URL地址]
     * @param  string $post [请求的参数]
     * @return  string
     */
    public static function postCurls($url, $post)
    {
        try {
            $curl = curl_init(); // 启动一个CURL会话
            curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
            curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
            curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
            $res = curl_exec($curl); // 执行操作
            curl_close($curl); // 关闭CURL会话
        } catch (\Exception $exception) {
            $res = '';
        }
        return $res;
    }

    /**
     * 获取access_token
     * 官方文档 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183
     */
    private static function getAccessToken()
    {
        try {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::APP_ID . "&secret=" . self::SECRET;
            //get方式获取
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $data = curl_exec($ch);
            $data = json_decode($data, true);
            curl_close($ch);
        } catch (\Exception $exception) {
            return [];
        }
        return $data;
    }
}