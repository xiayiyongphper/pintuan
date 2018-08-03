<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "new_act_product".
 *
 * @property string $id 自增ID
 * @property int $act_id 活动id，new_user_activity表的主键id
 * @property string $product_id 商品id
 * @property string $spec_id 规格id
 * @property string $price 新人价(分)
 * @property int $del 是否删除：1-正常，2-删除
 */
class NewActProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_act_product';
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
            [['act_id', 'product_id', 'spec_id', 'price','wholesaler_id', 'del'], 'integer'],
            [['product_id', 'spec_id','wholesaler_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'act_id' => 'Act ID',
            'product_id' => 'Product ID',
            'spec_id' => 'Spec ID',
            'price' => 'Price',
            'wholesaler_id' => 'wholesaler_id',
            'del' => 'Del',
        ];
    }

    /**
     *  获取活动商品列表
     * @param $actId
     */
    public function getGoodList($actId)
    {
        $where = [
           'new_act_product.act_id'=>$actId
        ];
        $select = 'new_act_product.*,product.name as pro_name,specification.item_detail,specification.price as spe_price';
        $goods = self::find()->select($select)->leftJoin('product','product.id=new_act_product.product_id')
                                            ->leftJoin('specification','specification.id=new_act_product.spec_id')
                                            ->where($where)->asArray()->all();

        if ($goods) {
            $wholesalerModel = new \backend\models\Wholesaler();
            foreach ($goods as $key=>$val) {
                $winfo = $wholesalerModel::findOne($val['wholesaler_id']);
                $goods[$key]['wholesaler_name'] = $winfo->name;
            }
        }

        return $goods;
    }
}
