<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Crontab;

/**
 * CrontabSearch represents the model behind the search form of `backend\models\Crontab`.
 */
class CrontabSearch extends Crontab
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'sticky', 'status'], 'integer'],
            [['name', 'route', 'cron_format', 'created_at', 'updated_at', 'from_time', 'to_time', 'notes', 'params'], 'safe'],
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
        $query = Crontab::find();

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
            'entity_id' => $this->entity_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sticky' => $this->sticky,
            'status' => $this->status,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'cron_format', $this->cron_format])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
