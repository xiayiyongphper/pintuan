<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $img_url 图片地址
 * @property string $type 类型 1商品 2拼团
 * @property string $value 类型值
 * @property int $sort 权重 升序
 * @property int $status 状态 1启用 2禁用
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner';
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
            [['title', 'type', 'value'], 'required'],
            [['type', 'sort', 'status'], 'integer'],
            [['value', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 10],
            [['img_url', 'type'], 'string', 'max' => 255],
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
            'img_url' => '图片',
            'type' => '类型',
            'value' => '类型值',
            'sort' => '权重',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
