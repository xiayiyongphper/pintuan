<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settlement_order".
 *
 * @property int $id 自增id
 * @property string $settlement_num 结算单编号
 * @property int $business_id 店铺或者供应商的id
 * @property string $business_name 商家名称
 * @property string $bank 开户银行
 * @property string $account 银行账号
 * @property string $account_name 开户名称
 * @property double $settlement_amount 提现金额
 * @property string $settlement_time 结算时间
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property int $pay_state 打款状态 1-未打款 2已打款
 * @property string $pay_time 打款时间
 * @property int $settlement_type 结算单类型 1-超市结算单 2-供货商结算单
 */
class SettlementOrder extends \yii\db\ActiveRecord
{
    public $file;// 导入文件时候使用 没实际意义
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settlement_order';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('orderDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['settlement_num', 'business_id', 'settlement_time', 'created_at', 'business_name', 'bank', 'account', 'account_name'], 'required'],
            [['business_id', 'pay_state', 'settlement_type'], 'integer'],
            [['settlement_amount'], 'number'],
            [['settlement_time', 'created_at', 'updated_at', 'pay_time', 'file'], 'safe'],
            [['settlement_num', 'business_name', 'bank', 'account', 'account_name'], 'string', 'max' => 255],
            [['settlement_num'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'settlement_num' => '结算单编号(唯一)',
            'business_id' => '关联的超市/供货商id',
            'business_name' => '商家名称',
            'bank' => '开户银行',
            'account' => '银行账号',
            'account_name' => '开户人名称',
            'settlement_amount' => '结算金额',
            'settlement_time' => '结算时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'pay_state' => '打款状态 1-未打款 2已打款',
            'pay_time' => '打款时间',
            'settlement_type' => '结算单类型 1-超市结算单 2-供货商结算单',
        ];
    }
}
