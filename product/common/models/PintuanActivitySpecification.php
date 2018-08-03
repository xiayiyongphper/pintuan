<?php

namespace common\models;

use framework\components\Date;
use service\tools\Tools;
use Yii;

/**
 * This is the model class for table "pintuan_activity_specification".
 *
 * @property int $id
 * @property int pintuan_activity_id 拼团活动id
 * @property int $specification_id 规格id  sku的id
 * @property int $pin_price 拼团价
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class PintuanActivitySpecification extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;//未删除的
    const DELETED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan_activity_specification';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('productDb');
    }

}
