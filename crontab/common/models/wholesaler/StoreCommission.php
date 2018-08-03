<?php

namespace common\models\wholesaler;

use Yii;

/**
 * This is the model class for table "user_store".
 *
 * @property int $id
 * @property int $commission_type
 * @property int $commission_val
 *
 */
class StoreCommission extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'store_commission';
    }

    public static function getDb()
    {
        return Yii::$app->get('wholesalerDb');
    }

}
