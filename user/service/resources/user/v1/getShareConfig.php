<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\ShareConfig;
use message\user\ShareConfigResponse;
use service\resources\ResourceAbstract;

class getShareConfig extends ResourceAbstract
{
    public function run($data)
    {
        $response = self::response();

        $shareConfig = ShareConfig::find()
            ->select(['type', 'position', 'img_url'])
            ->where(['status' => 1])
            ->asArray()->all();

        $response->setFrom(['share_configs' => $shareConfig]);
        return $response;
    }

    public static function request()
    {
        return true;
    }

    public static function response()
    {
        return new ShareConfigResponse();
    }

}