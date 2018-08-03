<?php

namespace backend\models;

use common\tools\Tools;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Product;

/**
 * ProductSearch represents the model behind the search form of `backend\models\Product`.
 */
class ProductSearch extends Product
{

    public $wholesaler_name;
    public $category_name;
    public $spu_import;
    public $sku_import;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'wholesaler_id', 'status', 'third_category_id', 'del'], 'integer'],
            [['name', 'images', 'description', 'unit', 'create_at', 'update_at', 'wholesaler_name', 'category_name'], 'safe'],
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

    public function search($params)
    {
        $query = Product::find()->joinWith('wholesaler')->joinWith('category');

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
            'product.id'          => $this->id,
            'product.status'      => $this->status,
//            'create_at' => $this->create_at,
//            'update_at' => $this->update_at,
//            'c.name' => $this->category_name,
            'product.del' => 1,
        ]);

        $query->andFilterWhere(['like', 'product.name', $this->name]);
        $query->andFilterWhere(['like', 'wholesaler.name', $this->wholesaler_name]);
        $query->andFilterWhere(['like', 'category.name', $this->category_name]);
//            ->andFilterWhere(['like', 'images', $this->images])
//            ->andFilterWhere(['like', 'description', $this->description])
//            ->andFilterWhere(['like', 'unit', $this->unit]);

//        echo $query->createCommand()->rawSql;exit;

        $query->orderBy('product.id desc');
        return $dataProvider;
    }

    public function search2($params, $pages, $pageSize)
    {
        $query = Product::find()->joinWith('wholesaler')->joinWith('specification');

        $query->andWhere([
            'product.status' => 1,
            'product.del'    => 1,
        ]);

        if ($params['kw']) {
            $query->andWhere(['or',
                ['like', 'product.name', $params['kw']],
                ['like', 'wholesaler.name', $params['kw']]]);
        }

        $pageSize = 4;

        $count = $query->count();

        $pageSum = ceil($count / $pageSize);
        $offset = ($pages - 1) * $pageSize;
        $last = $pages <= 1 ? 1 : $pages - 1;
        $next = $pages >= $pageSum ? $pageSum : $pages + 1;
        $query->limit($pageSize)->offset($offset);

        $query->orderBy('id desc');

//        echo $query->createCommand()->rawSql;exit;

        $data = $query->asArray()->all();

//        var_dump($data);
//        exit;

        //处理上一页码和下一页
        if ($pages == $last) {
            $last = 0;
        }

        if ($pages == $next) {
            $next = 0;
        }

        return [
            'list'    => $data,
            'last'    => $last,
            'curpage' => $pages,
            'next'    => $next,
            'pageSum' => $pageSum,
        ];
    }


    public function search3($params, $pages, $pageSize)
    {
        $query = Product::find()->joinWith('wholesaler');

        $query->andWhere([
            'product.status' => 1,
            'product.del'    => 1,
        ]);

        if ($params['kw']) {
            $query->andWhere(['or',
                ['like', 'product.name', $params['kw']],
                ['like', 'wholesaler.name', $params['kw']]]);
        }

        $pageSize = 4;

        $count = $query->count();

        $pageSum = ceil($count / $pageSize);
        $offset = ($pages - 1) * $pageSize;
        $last = $pages <= 1 ? 1 : $pages - 1;
        $next = $pages >= $pageSum ? $pageSum : $pages + 1;
        $query->limit($pageSize)->offset($offset);

        $query->orderBy('id desc');

//        echo $query->createCommand()->rawSql;exit;

        $data = $query->asArray()->all();

//        var_dump($data);
//        exit;

        //处理上一页码和下一页
        if ($pages == $last) {
            $last = 0;
        }

        if ($pages == $next) {
            $next = 0;
        }

        return [
            'list'    => $data,
            'last'    => $last,
            'curpage' => $pages,
            'next'    => $next,
            'pageSum' => $pageSum,
        ];
    }
}
