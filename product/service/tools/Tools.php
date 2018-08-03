<?php

namespace service\tools;

use common\models\PintuanActivity;
use common\models\PintuanActivityStore;
use framework\components\ToolsAbstract;

class Tools extends ToolsAbstract
{
    public static function getImage($img, $size = '388x388')
    {
        $search = ['source', '600x600', '180x180', '232x232', '388x388', '560x560', '1200x1200'];
        return str_replace($search, $size, $img);
    }

    /**
     * @param $store_id
     * @param $wholesaler_ids
     * @param $pintuan_activity_id
     * @return bool 小店是否参加拼团活动
     * 小店是否参加拼团活动
     */
    public static function pintuanWhetherInDeliveryRange($store_id, $wholesaler_ids, $pintuan_activity_id)
    {
        // 根据自提点id查询自定义的自提点的活动id
        $activityId_1 = PintuanActivityStore::find()->select('pintuan_activity_id')
            ->where(['store_id' => $store_id, 'del' => 1])->column();

        // 根据供应商id查询出关联的活动id
        $activityId_2 = PintuanActivity::find()->where(['wholesaler_id' => $wholesaler_ids, 'place_type' => 1, 'del' => 1])->column();

        $finalIds = array_unique(array_merge($activityId_1, $activityId_2));

        return in_array($pintuan_activity_id, $finalIds);
    }

}