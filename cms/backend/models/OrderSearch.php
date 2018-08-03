<?php

namespace backend\models;

use common\tools\Tools;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `backend\models\Order`.
 */
class OrderSearch extends Order
{

    public $user_nickName;
    public $store_name;
    public $created_at_from;
    public $created_at_to;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'amount', 'real_amount', 'type', 'store_id', 'pay_type', 'status', 'refund_status', 'receive_type'], 'integer'],
            [['created_at_to', 'created_at_from', 'user_nickName', 'store_name', 'order_number', 'create_at', 'update_at', 'refund_at', 'pay_at', 'receive_at', 'arrival_at', 'user_refund_reason', 'service_refund_reason'], 'safe'],
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
        $query = Order::find();

        $query->addSelect(['id' => 'order.id', 'order_number', 'store_name' => 'store.name',
            'user_nickName' => 'user.nick_name', 'create_at' => 'order.create_at', 'amount',
            'real_amount', 'status' => 'order.status','type' => 'order.type','user_id' => 'order.user_id',]);
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

        $query->leftJoin(['user' => 'pintuan_user.user'], 'order.user_id=user.id');
        $query->leftJoin(['store' => 'pintuan_wholesaler.store'], 'order.store_id=store.id');

        if ($this->created_at_from && $this->created_at_to) {
            $query->andFilterWhere(['between', 'order.create_at', $this->created_at_from, $this->created_at_to]);
        }

        if ($this->user_nickName) {
            $query->andFilterWhere(['like', 'user.nick_name', $this->user_nickName]);
        }

        if ($this->store_name) {
            $query->andFilterWhere(['like', 'store.name', $this->store_name]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'real_amount' => $this->real_amount,
            'type' => $this->type,
            'store_id' => $this->store_id,
            'pay_type' => $this->pay_type,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'status' => $this->status,
            'refund_status' => $this->refund_status,
            'pay_at' => $this->pay_at,
            'receive_at' => $this->receive_at,
            'receive_type' => $this->receive_type,
            'arrival_at' => $this->arrival_at,
            //'pintuan_id' => $this->pintuan_id,
        ]);

        $query->andFilterWhere(['like', 'order_number', $this->order_number]);
        $query->orderBy('order.id desc');

        return $dataProvider;
    }
}
