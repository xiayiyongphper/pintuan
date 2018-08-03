<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PintuanUser;

/**
 * PuserSearch represents the model behind the search form of `backend\models\PintuanUser`.
 */
class PuserSearch extends PintuanUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'has_order', 'own_store_id', 'is_robot', 'del'], 'integer'],
            [['auth_token', 'open_id', 'union_id', 'session_key', 'nick_name', 'language', 'city', 'province', 'country', 'avatar_url', 'phone', 'real_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = PintuanUser::find();

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

         //如果尚未传值，则只查询正常的用户
        if (empty($this->is_robot)) {
            $this->is_robot = 1;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gender' => $this->gender,
            'has_order' => $this->has_order,
            'own_store_id' => $this->own_store_id,
            'is_robot' => $this->is_robot,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'del' => $this->del,
        ]);

        $query->andFilterWhere(['like', 'auth_token', $this->auth_token])
            ->andFilterWhere(['like', 'open_id', $this->open_id])
            ->andFilterWhere(['like', 'union_id', $this->union_id])
            ->andFilterWhere(['like', 'session_key', $this->session_key])
            ->andFilterWhere(['like', 'nick_name', $this->nick_name])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'avatar_url', $this->avatar_url])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'real_name', $this->real_name]);

        return $dataProvider;
    }
}
