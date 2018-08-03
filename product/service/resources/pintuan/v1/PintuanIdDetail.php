<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanActivity;
use message\product\PintuanDetailReq;
use message\product\PintuanIdDetailRes;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: wjq310
 * Date: 2018/07
 * Time: 12:06
 * purpose: 拼团详情
 */
class PintuanIdDetail extends ResourceAbstract
{
    public function run($data)
    {
        /** @var PintuanDetailReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();
        $responseData = [];
        Tools::log($request->toArray(), 'PintuanIdDetail.log');

        $pintuan_id = $request->getPintuanId();

        $conditions = [
            "pintuan.del" => 1,
            "pintuan.id" => $pintuan_id
        ];
        $res = Pintuan::find()->select(['pintuan.id as id','pintuan.end_time','pintuan.member_num as pintuan_num',
            'pintuan_activity.member_num as pintuan_activity_num','pintuan_activity.id as activity_id'])
            ->leftJoin([PintuanActivity::tableName()],'pintuan_activity.id = pintuan.pintuan_activity_id')
            ->where($conditions)->asArray()->one();

        Tools::log("res: ".var_export($res,true),"pintuan_res.log");

        if(empty($res)){
            $response->setFrom($responseData);
            return $response;
        }
        $responseData['id'] = $res['id'];
        $responseData['pintuan_need_num'] = 0;

        if($res['pintuan_num'] < $res['pintuan_activity_num']){
            $responseData['pintuan_need_num'] = $res['pintuan_activity_num']-$res['pintuan_num'];
        }
        $responseData['end_time'] = $res['end_time'];
        $response->setFrom($responseData);
        return $response;
    }

    public static function request()
    {
        return new PintuanDetailReq();
    }

    public static function response()
    {
        return new PintuanIdDetailRes();
    }
}