<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5 0005
 * Time: 17:24
 */

namespace backend\models;

use yii\db\ActiveRecord;
use yii;


class PintuanUserStore extends ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_store';
    }


    public static function getDb()
    {
        return Yii::$app->get('userDb');
    }

    public function findAllUser()
    {
        return static::find()->all();
    }


    public static function findById($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    public function getList($id)
    {
        return self::find()->where(['user_id' => $id])->asArray()->all();
    }

    public function updateById($store_id){
    }
}
