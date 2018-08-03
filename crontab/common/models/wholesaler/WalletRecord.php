<?php

namespace common\models\wholesaler;

use Yii;

/**
 * This is the model class for table "wallet_record".
 *
 * @property int $id
 * @property int $store_id 店铺id
 * @property string $record_number 流水号
 * @property int $amount 变动金额（分，提现为负值、扣除为负值）
 * @property int $type 类型：1-佣金转入，2-提现  3--后台导入奖金 4--后台导入罚金扣除
 * @property int $balance 余额（分）变化前钱包余额
 * @property int $status 状态：0-无状态，佣金转入为0,1-待打款，2-已打款
 * @property string $remit_at 打款时间
 * @property string $bonus_type 后台导入的奖金类型 文字
 * @property string $remark 备注（前端用）
 * @property int $commission_id 关联佣金表commission_record的id 当type为佣金转入时
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $del 是否删除：1-正常，2-删除
 * @property string $money_remark 打款备注
 * @property int $after_balance 后台导入后的钱包余额（变化后的钱包余额）
 * @property int $user_id 后台导入人的用户id
 * @property string $import_remark 后台导入备注
 * @property string $import_ip 后台导入时的所在IP地址
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
            [['store_id', 'record_number', 'amount', 'type', 'balance', 'create_at', 'update_at', 'after_balance'], 'required'],
            [['store_id', 'amount', 'type', 'balance', 'status', 'commission_id', 'del', 'after_balance', 'user_id'], 'integer'],
            [['remit_at', 'create_at', 'update_at', 'user_id', 'import_remark', 'import_ip'], 'safe'],
            [['record_number', 'import_ip'], 'string', 'max' => 20],
            [['bonus_type', 'remark', 'money_remark', 'import_remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'record_number' => 'Record Number',
            'amount' => 'Amount',
            'type' => 'Type',
            'balance' => 'Balance',
            'status' => 'Status',
            'remit_at' => 'Remit At',
            'bonus_type' => 'Bonus Type',
            'remark' => 'Remark',
            'commission_id' => 'Commission ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
            'money_remark' => 'Money Remark',
            'after_balance' => 'After Balance',
            'user_id' => 'User ID',
            'import_remark' => 'Import Remark',
            'import_ip' => 'Import Ip',
        ];
    }

    // 生成流水单号
    public static function recordNumber()
    {
        $repeat = true;
        while ($repeat) {
            $record_number = date('YmdHis') . rand(100000, 999999);
            // 查询是否重复
            $hasRepeat = WalletRecord::findOne(['record_number' => $record_number]);
            if (!$hasRepeat) {
                $repeat = false;
                return $record_number;
            }
        }
        return false;
    }
}
