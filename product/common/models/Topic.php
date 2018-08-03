<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "topic".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $products 商品id逗号分开
 * @property string $img_url 图片地址
 * @property int $type 类型 1图片链接商品列表 2商品列表
 * @property int $status 状态 1启用 2禁用
 * @property int $sort 权重
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topic';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('commonDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'products'], 'required'],
            [['products'], 'string'],
            [['type', 'status', 'sort'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 10],
            [['img_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'products' => '商品规格',
            'img_url' => '图片',
            'type' => '类型',
            'status' => '状态',
            'sort' => '权重',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
