<?php

namespace service\resources\pintuan\v1;

use common\models\Topic;
use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\PintuanUser;
use framework\data\Pagination;
use framework\Exception;
use message\product\PintuanActivityListReq;
use message\product\PintuanActivityListRes;
use service\resources\ResourceAbstract;
use service\tools\Tools;
use yii\db\Expression;

class topicPintuanList extends ResourceAbstract
{
    const PAGE_SIZE = 30;

    public function run($data)
    {
        /** @var PintuanActivityListReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        $topic = Topic::find()->select('products')
            ->where(['id' => $request->getTopicId(), 'status' => 1])
            ->asArray()->one();

        Tools::log($request, '1111111111.log');
        if (!$topic || !$topic['products']) {
            Exception::systemNotFound();
        }

        $pintuanIds = array_map('intval', explode(',', $topic['products']));

        $query = PintuanActivity::find()->alias('act')
            ->leftJoin(['s' => PintuanActivitySpecification::tableName()], 's.pintuan_activity_id = act.id')
            ->where(['act.id' => $pintuanIds, 'act.status' => 1, 'act.del' => 1, 's.del' => 1])
            ->andWhere(['wholesaler_id' => $request->getWholesalerId()])
            ->andWhere(['<=', 'act.start_time', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'act.end_time', date('Y-m-d H:i:s')]);

        $query->select(['act.id', 'act.title', 'act.cover_picture', new Expression('min(s.pin_price) as pin_price'), 'act.end_time']);
        $query->groupBy(['act.id']);
        $query->orderBy('act.sort ASC');
        $total = $query->count();

        $pages = new Pagination();
        $pages->setTotalCount($total);
        $pageSize = $request->getPageSize() ?: self::PAGE_SIZE;
        $pages->setCurPage($request->getPage());
        $pages->setPageSize($pageSize);
        $query->offset($pages->getOffset())->limit($pageSize);

        $activityArr = $query->asArray()->all();
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

            $colonel = $colonel->asArray()->all();
            if (!empty($colonel)) {
                foreach ($colonel as $v) {
                    $activityArr[$key]['colonel'][] = $v['avatar_url'];
                }
            }
        }

        $result['activity'] = $activityArr;
        $result['pages'] = Tools::getPagination($pages);

        $response->setFrom($result);
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