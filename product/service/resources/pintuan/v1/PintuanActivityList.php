<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\PintuanActivityStore;
use common\models\PintuanUser;
use message\product\PintuanActivityListReq;
use message\product\PintuanActivityListRes;
use service\resources\ResourceAbstract;
use service\tools\Tools;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 乐小拼C端首页 拼团活动列表
 */
class PintuanActivityList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var PintuanActivityListReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 根据自提点id查询自定义的自提点的活动id
        $activityId_1 = PintuanActivityStore::find()->select('pintuan_activity_id')
            ->where(['store_id' => $request->getStoreId(), 'del' => 1])->column();
        // 根据供应商id查询出关联的活动id
        $activityId_2 = PintuanActivity::find()->where(['wholesaler_id' => $request->getWholesalerId(), 'place_type'=> 1, 'del' => 1])->column();
        $finalIds = array_unique(array_merge($activityId_1, $activityId_2));
        // 查询拼团活动信息 展示30个拼团活动，最后创建的排最前
        $activityArr = PintuanActivity::find()->select(['act.id', 'act.title', 'act.cover_picture', new Expression('min(s.pin_price) as pin_price'), 'act.end_time'])
            ->alias('act')
            ->leftJoin(['s' => PintuanActivitySpecification::tableName()],'s.pintuan_activity_id = act.id')
            ->where(['act.id' => $finalIds, 'act.status' => 1, 'act.del' => 1,'s.del' => 1])
            ->andWhere(['<=', 'act.start_time', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'act.end_time', date('Y-m-d H:i:s')])
            ->groupBy(['act.id'])
            ->orderBy('act.sort ASC')
            ->limit(15);
//        Tools::log($activityArr->createCommand()->rawSql,'pintuan_activity_list.log');
        $activityArr = $activityArr->asArray()->all();
        /**@var PintuanActivity $item * */
        foreach ($activityArr as $key => $item) {
            // 金额从分转换成元
            $activityArr[$key]['pin_price'] = $item['pin_price'] / 100;

            // 查询参与人数和参与人头像
            $memberNum = Pintuan::find()->select('SUM(member_num) as member_total')
                ->where(['pintuan_activity_id' => $item['id'], 'status' => 1])->asArray()->one();
            $activityArr[$key]['complete_member_num'] = $memberNum['member_total'];
            // 查询出该活动参与人员的头像
            $colonel = PintuanUser::find()->select('u.nick_name,u.avatar_url')->alias('u')
                ->leftJoin(['p' => Pintuan::tableName()], 'u.pintuan_id=p.id')
                ->where(['p.pintuan_activity_id' => $item['id'], 'p.status' => 1])
                ->andWhere(['!=', 'u.nick_name', ''])
                ->andWhere(['!=', 'u.avatar_url', ''])
                ->limit(2)->orderBy('p.id DESC');
            Tools::log($colonel->createCommand()->getRawSql(), 'colonel.log');
            $colonel = $colonel->asArray()->all();
            if (!empty($colonel)) {
                foreach ($colonel as $v) {
                    $activityArr[$key]['colonel'][] = $v['avatar_url'];
                }
            }
        }

        $response->setFrom(['activity' => $activityArr]);
        return $response;
    }

    public static function request()
    {
        return new PintuanActivityListReq();
    }

    public static function response()
    {
        return new PintuanActivityListRes();
    }
}