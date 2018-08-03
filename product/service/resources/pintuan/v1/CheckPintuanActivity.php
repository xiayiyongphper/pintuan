<?php

namespace service\resources\pintuan\v1;

use common\models\PintuanActivityStore;
use framework\components\ToolsAbstract;
use message\product\CheckPintuanActivityRequest;
use message\product\PintuanActivity;
use message\product\PintuanActivityDetailReq;
use message\product\PintuanActivityDetailRes;
use message\product\PintuanActivityListReq;
use service\resources\Exception;
use service\resources\ResourceAbstract;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/06
 * Time: 15:09
 * purpose: 验证拼团是否存在
 */
class CheckPintuanActivity extends ResourceAbstract
{
    public function run($data)
    {
        /** @var CheckPintuanActivityRequest $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 根据活动id查询活动详情
        $date = date('Y-m-d H:i:s');
        /** @var \common\models\PintuanActivity $pintuanActivity */
        $pintuanActivity = \common\models\PintuanActivity::find()->where(['id' => $request->getPintuanActivityId(), 'status' => 1, 'del' => 1])
            ->andWhere(['<=', 'start_time', $date])->andWhere(['>', 'end_time', $date])->one();

        if(!$pintuanActivity || empty($pintuanActivity->id)){
            Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
        }
        $response->setId($pintuanActivity->id);
        return $response;
    }

    public static function request()
    {
        return new CheckPintuanActivityRequest();
    }

    public static function response()
    {
        return new PintuanActivity();
    }
}