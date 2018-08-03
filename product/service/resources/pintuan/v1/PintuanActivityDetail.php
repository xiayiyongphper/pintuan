<?php

namespace service\resources\pintuan\v1;

use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\PintuanActivityStore;
use message\product\PintuanActivityDetailReq;
use message\product\PintuanActivityDetailRes;
use service\resources\ResourceAbstract;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 拼团活动详情
 */
class PintuanActivityDetail extends ResourceAbstract
{
    public function run($data)
    {
        /** @var PintuanActivityDetailReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 根据自提点id查询自定义的自提点的活动id
        $activityId_1 = PintuanActivityStore::find()->select('pintuan_activity_id')->where(['store_id' => $request->getStoreId(), 'del' => 1])->column();
        // 根据供应商id查询出关联的活动id
        $activityId_2 = PintuanActivity::find()
            ->where(['id' => $request->getActivityId(), 'wholesaler_id' => $request->getWholesalerId(), 'del' => 1])
            ->column();
        $finalIds = array_unique(array_merge($activityId_1, $activityId_2));
        // 查询拼团活动信息 展示30个拼团活动，最后创建的排最前
        $activityArr = PintuanActivity::find()
            ->select(['act.id', 'act.cover_picture', new Expression('min(s.pin_price) as pin_price'), 'act.end_time'])
            ->alias('act')
            ->leftJoin(['s' => PintuanActivitySpecification::tableName()],'s.pintuan_activity_id = act.id')
            ->where(['act.id' => $finalIds, 'act.status' => 1, 'act.del' => 1,'s.del' => 1])
            ->andWhere(['<=', 'act.start_time', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'act.end_time', date('Y-m-d H:i:s')])
            ->groupBy(['act.id'])
            ->asArray()->all();

        $respData = [
            'activity' => $activityArr
        ];

        $response->setFrom($respData);
        return $response;
    }

    public static function request()
    {
        return new PintuanActivityDetailReq();
    }

    public static function response()
    {
        return new PintuanActivityDetailRes();
    }
}