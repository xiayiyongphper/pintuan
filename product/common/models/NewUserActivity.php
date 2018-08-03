<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "new_user_activity".
 *
 * @property int $id 活动ID
 * @property string $act_code 活动编码
 * @property string $act_name 活动名称
 * @property string $start_at 开始时间
 * @property string $end_at 结束时间
 * @property int $province 省份编码
 * @property int $city 城市编码
 * @property int $place_type 自提点类型，1同供货商配送范围，2手动选择自提点
 * @property int $status 活动状态，1开启，2关闭
 * @property int $operate_status 活动运营状态，1未开始，2进行中，3已结束
 * @property int $del 是否删除：1-正常，2-删除
 * @property int $order_num 活动订单数
 * @property int $browse_num 活动浏览数
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class NewUserActivity extends \yii\db\ActiveRecord
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 2;

    const NOT_DELETED = 1;
    const DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_user_activity';
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
            [['start_at', 'end_at', 'place_type', 'status', 'operate_status'], 'required'],
            [['start_at', 'end_at', 'created_at', 'updated_at'], 'safe'],
            [['province', 'city', 'place_type', 'status', 'operate_status', 'del', 'order_num', 'browse_num'], 'integer'],
            [['act_code'], 'string', 'max' => 50],
            [['act_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'             => '活动ID',
            'act_code'       => '活动编码',
            'act_name'       => '活动名称',
            'start_at'       => '开始时间',
            'end_at'         => '结束时间',
            'province'       => '省份编码',
            'city'           => '城市编码',
            'place_type'     => '自提点类型，1同供货商配送范围，2手动选择自提点',
            'status'         => '活动状态，1开启，2关闭',
            'operate_status' => '活动运营状态，1未开始，2进行中，3已结束',
            'del'            => '是否删除：1-正常，2-删除',
            'order_num'      => '活动订单数',
            'browse_num'     => '活动浏览数',
            'created_at'     => '创建时间',
            'updated_at'     => '更新时间',
        ];
    }

    public static function getNewUserActivityOne($city)
    {
        $date = date('Y-m-d H:i:s');
        $query = self::find()
            ->where([
                'city'   => $city,
                'status' => self::STATUS_OPEN,
                'del'    => self::NOT_DELETED
            ])
            ->andWhere(['<=', 'start_at', $date])
            ->andWhere(['>', 'end_at', $date]);

        return $query->one();
    }
}
