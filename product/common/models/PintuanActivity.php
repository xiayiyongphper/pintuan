<?php

namespace common\models;

use service\tools\Tools;
use Yii;
use framework\db\ActiveRecord;

/**
 * This is the model class for table "pintuan_activity".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $cover_picture 拼团活动封面图
 * @property int $product_id 商品id spu的id
 * @property int $wholesaler_id 供应商id
 * @property string $start_time 开始时间
 * @property string $end_time 结束时间
 * @property int $type 拼团类型：1-单点拼团，2-多点拼团
 * @property string $strategy 人数策略，json格式，如："{"base_member_num":{"after_start_min":1,"member_num":9},"auto_increment":{"before_end_min":60,"increment_cycle_min":10},"fill_before_end":{"before_end_min":5}}"
 * @property int $member_num 参团人数
 * @property int $continue_pintuan 超过可继续拼团 1是 2否
 * @property string $sort    排序权重
 * @property string $create_at
 * @property string $update_at
 * @property int $status 拼团活动是否手动结束：1未结束，2已结束
 * @property int $del 是否删除：1-正常，2-删除
 * @property int $place_type 自提点类型，1同供货商配送范围，2手动选择自提点
 */
class PintuanActivity extends ActiveRecord
{
    const PLACE_TYPE_AS_WHOLESALER = 1;
    const PLACE_TYPE_ASSIGN_STORES = 2;

    public $store_ids;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan_activity';
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
            [['product_id',  'wholesaler_id', 'start_time', 'end_time', 'member_num', 'create_at'], 'required'],
            [['product_id', 'wholesaler_id','type', 'member_num', 'continue_pintuan', 'del', 'status'], 'integer'],
            [['start_time', 'end_time', 'create_at', 'update_at', 'sort', 'update_at'], 'safe'],
            [['title', 'cover_picture'], 'string', 'max' => 255],
            [['strategy'], 'string', 'max' => 256],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * 一个商品任意时间点只能参加一个有效拼团活动，所以这里只取一个
     * @param $productId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getByProductId($productId)
    {
        $now = date('Y-m-d H:i:s');
        $res = self::find()->where([
            'product_id' => $productId,
            'status' => 1,
            'del' => 1
        ])->andWhere(['<', 'start_time', $now])
            ->andWhere(['>', 'end_time', $now]);
        Tools::log($res->createCommand()->rawSql, 'pro.log');
        $res = $res->one();

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '活动标题',
            'cover_picture' => '封面图片',
            'product_id' => 'spu商品id',
            'wholesaler_id' => '供应商的id',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'type' => '拼团类型：1-单点拼团，2-多点拼团',
            'strategy' => '人数策略',
            'member_num' => '参团人数',
            'continue_pintuan' => '超过可继续拼团 1是 2否',
            'sort' => '排序',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'status' => '拼团活动是否手动结束：1未结束，2已结束',
            'del' => '是否删除：1-正常，2-删除',
        ];
    }

    public static function getPintuanActivityByPintuanId($pintuanId){
        $now = date("Y-m-d H:i:s");
        /** @var PintuanActivity $pintuanActivity */
        $pintuanActivity = self::find()
            ->alias('act')
            ->leftJoin(['p' => Pintuan::tableName()],'p.pintuan_activity_id = act.id')
            ->where(['p.id' => $pintuanId,'act.del' => 1,'p.status' => 1])
            ->andWhere(['<','act.start_time',$now])
            ->andWhere(['>','act.end_time',$now])
            ->one();

        if(!$pintuanActivity) return $pintuanActivity;

        $storeIds = [];
        $pintuanActStores = PintuanActivityStore::findAll([
            'pintuan_activity_id' => $pintuanActivity->id,
            'del' => 1,
        ]);

        //Tools::log($storeIds,'buy_items.log');
        if($pintuanActStores){
            foreach ($pintuanActStores as $item){
                $storeIds[] = $item->store_id;
            }
        }

        $pintuanActivity->store_ids = $storeIds;
       // Tools::log($pintuanActivity->store_ids,'buy_items.log');
        return $pintuanActivity;
    }
}
