<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Store;

/**
 * StoreSearch represents the model behind the search form of `backend\models\Store`.
 */
class StoreSearch extends Store
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'wallet', 'province', 'city', 'district', 'area_id', 'owner_user_id', 'status', 'type', 'contractor_id', 'service_id', 'delivery_type', 'del'], 'integer'],
            [['name', 'auth_token', 'open_id', 'union_id', 'address', 'detail_address', 'lat', 'lng', 'store_phone', 'created_at', 'updated_at', 'apply_at', 'business_license_no', 'business_license_img', 'store_front_img', 'open_time_range', 'bank', 'account', 'account_name', 'mini_program_qrcode', 'receive_goods_qrcode', 'wx_qrcode', 'owner_user_photo'], 'safe'],
            [['commission_coefficient'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Store::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'wallet' => $this->wallet,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'area_id' => $this->area_id,
            'owner_user_id' => $this->owner_user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'apply_at' => $this->apply_at,
            'type' => $this->type,
            'contractor_id' => $this->contractor_id,
            'service_id' => $this->service_id,
            'delivery_type' => $this->delivery_type,
            'commission_coefficient' => $this->commission_coefficient,
            'del' => $this->del,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'auth_token', $this->auth_token])
            ->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'union_id', $this->union_id])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'detail_address', $this->detail_address])
            ->andFilterWhere(['like', 'lat', $this->lat])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'store_phone', $this->store_phone])
            ->andFilterWhere(['like', 'business_license_no', $this->business_license_no])
            ->andFilterWhere(['like', 'business_license_img', $this->business_license_img])
            ->andFilterWhere(['like', 'store_front_img', $this->store_front_img])
            ->andFilterWhere(['like', 'open_time_range', $this->open_time_range])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'mini_program_qrcode', $this->mini_program_qrcode])
            ->andFilterWhere(['like', 'receive_goods_qrcode', $this->receive_goods_qrcode])
            ->andFilterWhere(['like', 'wx_qrcode', $this->wx_qrcode])
            ->andFilterWhere(['like', 'owner_user_photo', $this->owner_user_photo]);

        return $dataProvider;
    }
}
