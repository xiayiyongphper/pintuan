<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wholesaler_district".
 *
 * @property int $id
 * @property int $wholesaler_id 供应商id
 * @property int $province 省编码
 * @property int $city 城市编码
 * @property int $district 区域编码
 * @property int $del 是否删除：1-正常，2-删除
 */
class WholesalerDistrict extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;//未删除的

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wholesaler_district';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wholesalerDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wholesaler_id', 'province', 'city', 'district', 'del'], 'integer'],
            [['province', 'city'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wholesaler_id' => '供应商id',
            'province' => '省编码',
            'city' => '城市编码',
            'district' => '区域编码',
            'del' => '是否删除：1-正常，2-删除',
        ];
    }

    public static function getGroupList($key, $value, $wholesalerId)
    {
        $query = self::find()->where(['wholesaler_id' => $wholesalerId]);
        $query->andWhere(['del' => 1]);
        if ($value) {
            $query->andWhere([$key => $value]);
        }
        return $query->groupBy($key)->asArray()->all();
    }

    public static function getList($wholesalerId, $limit, $offset)
    {
        $query = self::find()->where(['wholesaler_id' => $wholesalerId]);
        $query->andWhere(['del' => 1]);
        if ($limit) {
            $query->limit($limit)->offset($offset);
        }
        return $query->asArray()->all();
    }

    public static function getDistinctList($wholesalerId, $city)
    {
        $query = self::find()->where(['wholesaler_id' => $wholesalerId]);
        $query->andWhere(['city_code' => $city]);
        return $query->asArray()->all();
    }
}
