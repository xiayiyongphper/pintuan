<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "salesrule_product".
 *
 * @property int $id
 * @property int $rule_id 规则id
 * @property int $product_id 商品id
 */
class SalesruleProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salesrule_product';
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
            [['rule_id', 'product_id'], 'required'],
            [['rule_id', 'product_id'], 'integer'],
            [['rule_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => 'Rule ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * 获取商品明细
     * @param $salesruleId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGoodList($salesruleId)
    {
        $salesruleProductList = self::find()->where(['rule_id'=>$salesruleId])->asArray()->all();
        //获取优惠商品明细
        $ids = [];
        $goodList = [];
        if ($salesruleProductList) {
            foreach ($salesruleProductList as $val) {
                $ids[] = $val['product_id'];
            }
        }

        if ($ids) {
            $productModel = new \backend\models\Product();
            $select = 'id,name,wholesaler_id';
            $where = [
                'in', 'id', $ids
            ];
            $goodList = $productModel::find()->select($select)->where($where)->asArray()->all();
            if ($goodList) {
                $wholesalerModel = new \backend\models\Wholesaler();
                foreach ($goodList as $key=>$good) {
                    $winfo = $wholesalerModel::findOne($good['wholesaler_id']);
                    $goodList[$key]['wholesaler_name'] = $winfo->name;
                }
            }
        }

        return $goodList;
    }
}
