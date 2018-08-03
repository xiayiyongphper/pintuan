<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "improt_store_wallet".
 *
 * @property string $id 自增id
 * @property string $uid 操作人员的uid
 * @property string $storeid 操作的店铺id
 * @property string $before_wallet 操作之前的钱包余额(分)
 * @property string $wallet 金额(分)
 * @property string $after_wallet 操作之后的钱包余额(分)
 * @property int $import_type 导入类型：1佣金转入 2体现
 * @property string $ip 操作ip地址
 * @property string $create_at 创建时间
 */
class ImprotStoreWallet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'improt_store_wallet';
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
            [['uid', 'storeid', 'before_wallet', 'wallet', 'after_wallet', 'import_type'], 'integer'],
            [['create_at'], 'required'],
            [['create_at','remark'], 'safe'],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'storeid' => 'Storeid',
            'before_wallet' => 'Before Wallet',
            'wallet' => 'Wallet',
            'after_wallet' => 'After Wallet',
            'import_type' => 'Import Type',
            'ip' => 'Ip',
            'create_at' => 'Create At',
            'remark'=>'remark',
        ];
    }
}
