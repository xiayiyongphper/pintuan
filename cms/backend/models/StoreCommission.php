<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "store_type".
 *
 * @property string $id 店铺类型ID
 * @property string $name 店铺类型名称
 * @property int $commission_type 佣金类型 1佣金系数 2现金
 * @property int $commission_val 佣金数值
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $del 是否删除：1-正常，2-删除
 */
class StoreCommission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_commission';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wholesalerDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['commission_type', 'commission_val', 'del'], 'integer'],
            [['create_at', 'update_at'], 'required'],
            [['create_at', 'update_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'commission_type' => 'Commission Type',
            'commission_val' => 'Commission Val',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
        ];
    }
}
