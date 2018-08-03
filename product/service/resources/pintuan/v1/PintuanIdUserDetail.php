<?php

namespace service\resources\pintuan\v1;

use common\models\PintuanUser;
use message\product\PintuanUserReq;
use message\product\PintuanUserRes;
use service\resources\ResourceAbstract;
use framework\components\ToolsAbstract;

/**
 * Created by PhpStorm.
 * User: wjq310
 * Date: 2018/07
 * Time: 10:49
 * purpose: 由pintuan_id 获取 pintuan_user 表的相关信息
 */
class PintuanIdUserDetail extends ResourceAbstract
{
    public function run($data)
    {
        /** @var PintuanActivityListReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();
        $pintuan_id = $request->getPintuanId();

        $pintuanUserArr = PintuanUser::find()->select(['id', 'user_id', 'nick_name', 'avatar_url', 'created_at as create_at'])
            ->where(['pintuan_id' => $pintuan_id])
            ->orderBy('created_at ASC')
            ->asArray()->all();
        ToolsAbstract::log("pintuanUserArr: ".var_export($pintuanUserArr,true),"PintuanIdUserDetail.log");
        $result['pintuan_user'] = $pintuanUserArr;
        $response->setFrom(ToolsAbstract::pb_array_filter($result));
        return $response;
    }

    public static function request()
    {
        return new PintuanUserReq();
    }

    public static function response()
    {
        return new PintuanUserRes();
    }
}