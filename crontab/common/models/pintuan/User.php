<?php

namespace common\models\pintuan;

use framework\components\ToolsAbstract;
use Yii;
use framework\db\ActiveRecord;


class User extends ActiveRecord
{
    private static $STATUS_MAP = [
        self::STATUS_END => '已结束',
        self::STATUS_STARTED_HAS_STOCK => '马上抢',
        self::STATUS_STARTED_NO_STOCK => '已抢光',
        self::STATUS_PREPARED => '即将开始'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('userDb');
    }

    /**
     * Author Jason Y.Wang
     * 判断秒杀商品是否选中
     * @param $customer_id
     * @param $product_id
     * @param $selected
     * @return string
     */
    public static function setSecKillProductIsSelected($customer_id, $product_id, $selected)
    {
        $redis = ToolsAbstract::getRedis();
        $redis->hSet('sk_products_selected_' . $customer_id, $product_id, $selected);
    }

}
