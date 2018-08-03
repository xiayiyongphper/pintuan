<?php

namespace common\models;

use Yii;

/**
 *
 * @property int $id 自增ID
 * @property int $rule_id 自增ID
 * @property int $product_id 自增ID
 */
class SalesRuleProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salesrule_product';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('orderDb');
    }
}
