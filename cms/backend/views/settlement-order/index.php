<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SettlementOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $settlement_type */
/* @var $name */

$this->title = $settlement_type . '结算单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settlement-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--    <p>-->
    <!--        <? //= Html::a('创建新的结算单', ['create'], ['class' => 'btn btn-success']) ?>  -->
    <!--    </p>-->

    <!--  筛选条件  -->
    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['class' => $searchModel, 'method' => 'get', 'id' => 'search']); ?>

            <div style="width: 400px;">
                <?= $form->field($searchModel, 'pay_state')->label('打款状态')->dropDownList([1 => '未打款', 2 => '已打款'], ['style' => 'width:100px;']) ?>

                <p></p>
                <p></p>
                <div class="form-group">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

        <div>
            <?= Html::a('导出结算单', ['export'], ['class' => 'btn btn-success']) ?>
        <p></p>
        <p></p>
            <?php $form = ActiveForm::begin(['action'=>Url::toRoute('/settlement-order/import'),'options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= Html::fileInput('file') ?>
        <p></p>
            <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
                'id',
                'settlement_num',
                'business_name',
                'bank',
                'account',
                'account_name',
                'settlement_amount',
//            'settlement_time',
                ['label' => '结算时间', 'attribute' => 'settlement_time', 'value' => 'settlement_time', 'filter' =>
                    \dosamigos\datepicker\DateRangePicker::widget([
                        'name' => "SettlementOrderSearch[settlement_time_from]",
                        'value' => $searchModel->settlement_time_from,
                        'nameTo' => "SettlementOrderSearch[settlement_time_to]",
                        'valueTo' => $searchModel->settlement_time_to,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-m-dd'
                        ]
                    ]),
                    'headerOptions' => ['width' => '300'],
                ],
                [
                    'attribute' => 'pay_state',
                    'label' => '打款状态',
                    'value' => function ($model) {
                        // 根据商品id查出商品名称
                        return $model->pay_state == 1 ? '未打款' : '已打款';
                    },
                ],
                'created_at',
                'updated_at',
                'pay_time',
                //'settlement_type',

//            ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
