<?php

namespace backend\models;

use backend\models\Product;
use common\tools\Tools;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PintuanActivity;
use yii\db\ActiveQuery;

/**
 * PintuanActivitySearch represents the model behind the search form of `app\models\PintuanActivity`.
 */
class PintuanActivitySearch extends PintuanActivity
{
    public $all;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'specification_id', 'pin_price', 'type', 'member_num', 'continue_pintuan', 'del','already_pin', 'status'], 'integer'],
            [['title', 'cover_picture', 'start_time', 'end_time', 'strategy', 'create_at', 'update_at', 'all'], 'safe'],
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
        $query = PintuanActivity::find();

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

        if (!$this->del) {
            $this->del = 1;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'specification_id' => $this->specification_id,
            'wholesaler_id' => $this->wholesaler_id,
            'pin_price' => $this->pin_price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'type' => $this->type,
            'member_num' => $this->member_num,
            'continue_pintuan' => $this->continue_pintuan,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'status' => $this->status,
            'already_pin' => $this->already_pin,
            'del' => $this->del,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'cover_picture', $this->cover_picture])
            ->andFilterWhere(['like', 'strategy', $this->strategy]);
        if ($this->all) {
            //查询出该商品的id集合
            $productIds = Product::find()->select('id')->where(['like', 'name', $this->all])->column();
            $productIds = $productIds ? $productIds : 0;
            $query->andFilterWhere(['or', ['id' => $this->all], ['title' => $this->all], ['product_id' => $productIds]]);
        }

        return $dataProvider;
    }
}
