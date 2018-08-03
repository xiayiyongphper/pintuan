<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "new_act_store".
 *
 * @property string $id 自增ID
 * @property int $act_id 活动id，new_user_activity表的主键id
 * @property string $store_id 店铺id
 * @property int $del 是否删除：1-正常，2-删除
 */
class NewActStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_act_store';
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
            [['act_id', 'store_id', 'del'], 'integer'],
            [['store_id'], 'required'],
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
            'store_id' => 'Store ID',
            'del' => 'Del',
        ];
    }

    /**
     *  获取活动自提点列表
     * @param $actId
     */
    public function getStoreList($actId)
    {
        $where = [
            'act_id'=>$actId
        ];
        $select = 'store_id';
        $storeList = self::find()->select($select)->where($where)->asArray()->all();

        if ($storeList) {
            $select2 = 'store.name as store_name,store.owner_user_name,store.store_phone,store.address,store.detail_address,region.name';
            $storeModel = new \backend\models\Store();
            foreach ($storeList as $key=>$val) {
                $where2 = ['store.id'=>$val['store_id']];
                $info = $storeModel::find()->select($select2)->leftJoin('region', 'region.code=store.city')->where($where2)->asArray()->one();
                $storeList[$key]['store_name'] = $info['store_name'];
                $storeList[$key]['city_name'] = $info['name'];
                $storeList[$key]['owner_user_name'] = $info['owner_user_name'];
                $storeList[$key]['store_phone'] = $info['store_phone'];
                $storeList[$key]['detail_address'] = $info['detail_address'];
                $storeList[$key]['address'] = $info['address'];
            }
        }

        return $storeList;
    }
}
