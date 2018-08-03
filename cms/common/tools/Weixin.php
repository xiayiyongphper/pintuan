<?php
/**
 * 微信小程序的公用方法
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 14:44
 */

namespace common\tools;

use common\tools\WeChatBaseApi;
use common\tools\Ftp;

class Weixin
{
    /**
     * 生成二维码(小程序码), 返回二维码图片link
     *
     * 1.调起微信api获取小程序码(返回图片数据流)
     * 2.保存小程序码为本地图片
     * 3.小程序码本地图片upload CDN，返回CDN 地址
     */
    public static function setQrCode($file_name, $scene='', $page = 'pages/home/home')
    {
        //调起微信api获取小程序码 //返回图片数据流
        //生成小程序二维码
        $wxacode_data = WeChatBaseApi::getWxacode($scene, $page);

        if ($wxacode_data == '') {
            throw new \Exception('生成小程序码失败');
        }
        if (strstr($wxacode_data, 'errcode')) {
            throw new \Exception('生成小程序码失败');
        }

        $save_dir = '/home/www/publish/pintuan-cms/backend/runtime/var/wxacode/';
        if (!file_exists($save_dir)) {
            mkdir($save_dir, 0777, true);
        }

        $file_path = $save_dir . $file_name;

        if (file_put_contents($file_path, $wxacode_data) === false) {
            throw new \Exception('保存小程序码图片失败');
        }

        //上传cdn，获取link
        $cdn_link = self::uploadToCdn($file_path, $file_name);
        if (empty($cdn_link)) {
            throw new \Exception('小程序码图片上传失败');
        }

        return $cdn_link;
    }

    /**
     * 上传cdn，获取CDN link
     * @param $file_path
     * @param $file_name
     * @return string
     * @throws \Exception
     */
    public static function uploadToCdn($file_path, $file_name)
    {
        $cdn_url = '';
        // 结果文件存储地址
        $uploader = new Ftp();
        $result   = json_decode($uploader->upload($file_path, $file_name,'store'), true);
        //string(145) "{"code":0,"msg":"","url":"http:\/\/assets.lelai.com\/images\/files\/store\/20180626\/source\/new_share_log_96fafaf747d7a9d49a01498f5186a9ea.png"}"
        if (isset($result['code']) && isset($result['url']) && $result['code'] == 0) {
            $cdn_url = $result['url'];
            unlink($file_path);
        }

        return $cdn_url;
    }
}