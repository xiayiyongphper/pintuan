<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pintuan;

/**
 * PintuanSearch represents the model behind the search form of `app\models\Pintuan`.
 */
class PintuanSearch extends Pintuan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pintuan_activity_id', 'create_user_id', 'member_num', 'store_id', 'del'], 'integer'],
            [['create_at'], 'safe'],
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
        $query = Pintuan::find();

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
            'pintuan_activity_id' => $this->pintuan_activity_id,
            'create_user_id' => $this->create_user_id,
            'member_num' => $this->member_num,
            'store_id' => $this->store_id,
            'create_at' => $this->create_at,
            'del' => $this->del,
        ]);

        return $dataProvider;
    }
}
