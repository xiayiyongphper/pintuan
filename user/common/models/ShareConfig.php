<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "share_config".
 *
 * @property int $id 自增ID
 * @property int $type 类型 1图片 2截屏
 * @property int $position 类型 1首页 2详情
 * @property string $img_url 图片地址
 * @property int $status 状态 1启用 2禁用
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ShareConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'share_config';
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
            [['type', 'status', 'position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'type' => '类型',
            'position' => '位置',
            'img_url' => '图片地址',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
