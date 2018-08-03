<?php

namespace common\models;

use framework\db\ActiveRecord;
use Yii;


class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'product';
    }

    public static function getDb()
    {
        return Yii::$app->get('pintuan_product');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wholesaler_id', 'lsin', 'barcode', 'first_category_id', 'second_category_id', 'third_category_id', 'name', 'price', 'special_price', 'sold_qty', 'real_sold_qty', 'qty', 'package_num', 'package_spe'], 'required'],

            [['price', 'special_price'], 'number'],
            [['special_from_date', 'special_to_date', 'shelf_time', 'created_at', 'updated_at'], 'safe'],
            [['gallery', 'specification', 'description'], 'string'],
            [['lsin'], 'string', 'max' => 32],
            [['barcode'], 'string', 'max' => 48],
            [['name'], 'string', 'max' => 128],
            [['brand'], 'string', 'max' => 100],
            [['origin'], 'string', 'max' => 60],
            [['package_spe'], 'string', 'max' => 10],
            [['package'], 'string', 'max' => 20],
            [['shelf_life'], 'string', 'max' => 50]
        ];
    }

}
