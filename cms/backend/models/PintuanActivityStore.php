<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pintuan_activity_store".
 *
 * @property int $id
 * @property int $pintuan_activity_id 拼团活动id
 * @property int $store_id
 * @property string $create_at
 * @property int $del
 */
class PintuanActivityStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan_activity_store';
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
            [['pintuan_activity_id', 'store_id', 'create_at', 'del'], 'required'],
            [['pintuan_activity_id', 'store_id', 'del'], 'integer'],
            [['create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pintuan_activity_id' => 'Pintuan Activity ID',
            'store_id' => 'Store ID',
            'create_at' => 'Create At',
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
            'pintuan_activity_id'=>$actId,
            'del'=>1
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
