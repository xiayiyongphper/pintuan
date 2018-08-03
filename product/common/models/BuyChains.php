<?php
namespace common\models;

use Yii;
use framework\db\ActiveRecord;

/**
 * This is the model class for table "buy_chains".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $image 封面图
 * @property int $product_id 商品id spu的id
 * @property int $wholesaler_id 供应商id
 * @property string $start_time 开始时间
 * @property string $end_time 结束时间
 * @property int $place_type 自提点类型，1同供货商配送范围，2手动选择自提点
 * @property int $status 手动结束，1未结束，2已结束
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class BuyChains extends ActiveRecord
{
    const PLACE_TYPE_AS_WHOLESALER = 1;
    const PLACE_TYPE_ASSIGN_STORES = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buy_chains';
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
            [['product_id', 'wholesaler_id', 'start_time', 'end_time', 'create_at', 'create_at'], 'required'],
            [['product_id', 'wholesaler_id', 'del', 'status', 'place_type'], 'integer'],
            [['start_time', 'end_time', 'create_at', 'update_at', 'sort', 'update_at'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

}