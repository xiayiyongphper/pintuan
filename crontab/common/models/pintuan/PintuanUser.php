<?php

namespace common\models\pintuan;

use framework\components\ToolsAbstract;
use Yii;
use framework\db\ActiveRecord;

/**
 * This is the model class for table "pintuan_user".
 *
 * @property int $id
 * @property int $pintuan_id 拼团id，对应pingtuan_product.pintuan表中的id
 * @property int $user_id 用户id
 * @property string $nick_name 微信昵称
 * @property string $avatar_url 微信头像
 * @property string $created_at
 */
class PintuanUser extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pintuan_user';
    }


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('productDb');
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
