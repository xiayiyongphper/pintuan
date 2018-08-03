<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "wallet_record".
 *
 * @property int $id
 * @property string $record_number 流水号
 * @property int $amount 变动金额（分，提现为负值）
 * @property int $type 类型：1-佣金转入，2-提现
 * @property int $balance 余额（分）
 * @property int $store_id 店铺id
 * @property int $status 状态：0-无状态，佣金转入为0,1-待打款，2-已打款
 * @property string $remit_at 打款时间
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $del 是否删除：1-正常，2-删除
 */
class WalletRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet_record';
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
            [['record_number', 'amount', 'type', 'balance', 'create_at', 'update_at'], 'required'],
            [['amount', 'type', 'balance', 'status', 'del'], 'integer'],
            [['remit_at', 'create_at', 'update_at','after_balance','user_id','import_remark','import_ip'], 'safe'],
            [['record_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_number' => '流水号',
            'amount' => '变动金额（分，提现为负值）',
            'type' => '类型：1-佣金转入，2-提现',
            'balance' => '余额（分）',
            'status' => '状态：0-无状态，佣金转入为0,1-待打款，2-已打款',
            'remit_at' => '打款时间',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'del' => '是否删除：1-正常，2-删除',
            'money_remark'=>'打款备注',
            'after_balance'=>'后台导入后的钱包余额',
            'user_id'=>'后台导入人的用户id',
            'import_remark'=>'后台导入备注',
            'import_ip'=>'后台导入时的所在IP地址',
        ];
    }

    public static function getWalletRecordDataProvider($store_id)
    {
        $query = self::find()->where(['store_id' => $store_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}
