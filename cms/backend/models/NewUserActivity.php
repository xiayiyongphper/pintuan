<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "new_user_activity".
 *
 * @property string $id 活动ID
 * @property string $act_code 活动编码
 * @property string $act_name 活动名称
 * @property string $start_at 开始时间
 * @property string $end_at 结束时间
 * @property string $province 省份编码
 * @property string $city 城市编码
 * @property int $place_type 自提点类型，1同供货商配送范围，2手动选择自提点
 * @property int $status 活动状态，1开启，2关闭
 * @property int $operate_status 活动运营状态，1未开始，2进行中，3已结束
 * @property int $del 是否删除：1-正常，2-删除
 * @property string $order_num 活动订单数
 * @property string $browse_num 活动浏览数
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class NewUserActivity extends \yii\db\ActiveRecord
{
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
            [['start_at', 'end_at', 'place_type', 'status', 'operate_status', 'created_at', 'updated_at'], 'required'],
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
            'id' => 'ID',
            'act_code' => 'Act Code',
            'act_name' => 'Act Name',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'province' => 'Province',
            'city' => 'City',
            'place_type' => 'Place Type',
            'status' => 'Status',
            'operate_status' => 'Operate Status',
            'del' => 'Del',
            'order_num' => 'Order Num',
            'browse_num' => 'Browse Num',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 新人活动的商品价格信息
     * @return mixed
     */
    public function getNewActProductPrices($spec_ids)
    {
          $curDate = date('Y-m-d H:i:s', time());
           $where = [
               'new_user_activity.status'=>1,
               'new_user_activity.del'=>1,
               'new_act_product.del'=>1
           ];
           $andWhere = [
               'and',
               ['<=', 'new_user_activity.start_at', $curDate],
               ['>', 'new_user_activity.end_at', $curDate]
           ];


        $andWhere2 = [];
        if ($spec_ids) {
            $andWhere2 = [
                'in', 'new_act_product.spec_id', $spec_ids
            ];
        }

        $select = 'new_act_product.act_id,new_act_product.product_id,new_act_product.spec_id,new_act_product.price';
        $res = self::find()->select($select)->leftJoin('new_act_product', 'new_act_product.act_id=new_user_activity.id')
                        ->where($where)->andWhere($andWhere)->andWhere($andWhere2)->asArray()->all();
        return $res;
    }
}
