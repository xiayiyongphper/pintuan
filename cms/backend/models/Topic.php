<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "topic".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $products 商品id逗号分开
 * @property string $img_url 图片地址
 * @property int $type 类型 1商品列表 2拼团列表
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
            'products' => '商品ID或者拼团活动ID',
            'img_url' => '图片',
            'type' => '专题类型',
            'status' => '状态',
            'sort' => '权重',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * (non-PHPdoc)
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert)
    {
        $curTime = date('Y-m-d H:i:s');
        if ($insert) {
            $this->created_at = $curTime;
        }

        $this->products = implode(',', array_map('intval', array_unique(explode(',', trim($this->products, ',')))));
        $this->updated_at = $curTime;
        return parent::beforeSave($insert);
    }
}
