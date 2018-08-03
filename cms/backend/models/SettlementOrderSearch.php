<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SettlementOrderSearch represents the model behind the search form of `app\models\SettlementOrder`.
 */
class SettlementOrderSearch extends SettlementOrder
{
    public $settlement_time_from;
    public $settlement_time_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'business_id', 'pay_state', 'settlement_type'], 'integer'],
            [['settlement_num', 'settlement_time', 'created_at', 'updated_at', 'pay_time', 'settlement_time_from', 'settlement_time_to', 'business_name', 'bank', 'account', 'account_name'], 'safe'],
            [['settlement_amount'], 'number'],
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
        $query = SettlementOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => 'desc']],// 默认创建活动倒序排列
            'pagination' => [
                'pageSize' => 10,// 每页10条数据
            ],
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
            'business_id' => $this->business_id,
            'settlement_amount' => $this->settlement_amount,
            'settlement_num' => $this->settlement_num,
            'settlement_time' => $this->settlement_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pay_state' => $this->pay_state,
            'pay_time' => $this->pay_time,
            'settlement_type' => $this->settlement_type,
        ]);


        $query->andFilterWhere(['like', 'business_name', $this->business_name])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'account_name', $this->account_name]);

        // 时间筛选
        if ($this->settlement_time_from || $this->settlement_time_to) {
            $query->andFilterWhere(['>=', 'settlement_time', $this->settlement_time_from]);
            $query->andFilterWhere(['<=', 'settlement_time', $this->settlement_time_to]);
        }

        return $dataProvider;
    }
}
