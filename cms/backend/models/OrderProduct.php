<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "order_product".
 *
 * @property int $id
 * @property int $order_id 订单id
 * @property int $product_id 商品id
 * @property int $number 商品数量
 * @property int $deal_price 购买成交价格，（分）
 * @property string $name 商品名称
 * @property int $wholesaler_id 供应商id
 * @property string $images 商品图片，图片可多张，json数组格式存储
 * @property string $description 商品描述，html格式
 * @property string $unit 单位
 * @property int $third_category_id 三级分类id
 * @property string $item_detail 规格属性详情，json格式
 * @property int $purchase_price 进价
 * @property int $pick_commission 自提佣金
 * @property int $promote_commission 推广佣金
 * @property int $price 售价（原价）
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class OrderProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_product';
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
            [['order_id', 'product_id', 'number', 'deal_price', 'name', 'wholesaler_id', 'third_category_id', 'purchase_price', 'pick_commission', 'promote_commission', 'price', 'create_at', 'update_at'], 'required'],
            [['order_id', 'product_id', 'number', 'deal_price', 'wholesaler_id', 'third_category_id', 'purchase_price', 'pick_commission', 'promote_commission', 'price', 'del'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['images', 'description', 'item_detail'], 'string', 'max' => 256],
            [['unit'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'order_id' => '订单号',
            'product_id' => '商品ID',
            'number' => '商品数量',
            'deal_price' => '购买成交价格',
            'name' => '商品名称',
            'wholesaler_id' => '供应商id',
            'images' => '商品图片',
            'description' => 'Description',
            'unit' => 'Unit',
            'third_category_id' => 'Third Category ID',
            'item_detail' => 'Item Detail',
            'purchase_price' => '进价',
            'pick_commission' => 'Pick Commission',
            'promote_commission' => 'Promote Commission',
            'price' => '售价（原价）',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
        ];
    }

    public static function orderProductDataProvider($id)
    {
        $query = OrderProduct::find()->where(['order_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;

    }
}
