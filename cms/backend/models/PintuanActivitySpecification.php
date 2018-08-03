<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pintuan_activity_specification".
 *
 * @property string $id
 * @property string $pintuan_activity_id 拼团活动id
 * @property string $specification_id 规格id
 * @property string $pin_price 拼团价格（分）
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class PintuanActivitySpecification extends \yii\db\ActiveRecord
{
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pintuan_activity_id', 'specification_id', 'pin_price', 'create_at', 'update_at'], 'required'],
            [['pintuan_activity_id', 'specification_id', 'pin_price', 'del'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pintuan_activity_id' => 'Pintuan Activity ID',
            'specification_id' => 'Specification ID',
            'pin_price' => 'Pin Price',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
        ];
    }
}
