<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '商品分类管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '新增分类'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            //'parent_id',
            [
                'attribute'=>'parent_id',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model->parent_id) {
                        $cmodel = new \backend\models\Category();
                        $info = $cmodel::findOne($model->parent_id);
                        return $info->name;
                    } else {
                       return '根分类';
                    }
                }

            ],
            [
                'attribute'=>'path',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $cmodel = new \backend\models\Category();
                    $names = $cmodel->getCategoryNames($model->id, '->');
                    return $names;
                }

            ],
            'level',
            [
                'attribute'=>'img',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->img,['height' => '120px']);
                }

            ],
            //'create_at',
            //'update_at',
            //'del',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
