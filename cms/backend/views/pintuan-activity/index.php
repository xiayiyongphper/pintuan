<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Product;
use backend\models\PintuanActivityStore;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use backend\models\Wholesaler;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PintuanActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\widgets\ActiveForm */

$this->title = '拼团活动列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-activity-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建新的拼团活动', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['class' => $searchModel, 'method' => 'get', 'id' => 'search']); ?>

            <div style="width: 400px;">

                <?= $form->field($searchModel, 'start_time')->label('开始时间')->widget(DateTimePicker::className(), [
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'startDate' => '01-Mar-2014 12:00 AM',
                        'todayHighlight' => true
                    ]
                ]);
                ?>

                <?= $form->field($searchModel, 'end_time')->label('结束时间')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'startDate' => '01-Mar-2014 12:00 AM',
                        'todayHighlight' => true
                    ]
                ]);
                ?>
            </div>
            <?= Html::textInput('PintuanActivitySearch[all]', $all, ['placeholder' => '请输入拼团ID/标题/商品名称', 'style' => 'border: 1px solid #ccc;border-radius: 4px;width:300px;height:40px;']) ?>

            <p></p>
            <p></p>

            <div class="form-group">
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('重置搜索', ['index'], ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            [
                'attribute' => 'cover_picture',
                'format' => ['image', ['width' => '300']],
                'value' => function ($model) {
                    return $model->cover_picture;
                },
            ],
//            'product_id',
//            'specification_id',
            [
                'attribute' => 'product_name',
                'label' => '商品名称',
                'value' => function ($model) {
                    // 根据商品id查出商品名称
                    $productInfo = Product::findOne(['id' => $model->product_id]);
                    return $productInfo ? $productInfo->name : '';
                },
            ],
//            'wholesaler_id',
            [
                'attribute' => 'wholesaler_name',
                'label' => '供应商名称',
                'value' => function ($model) {
                    // 根据商品id查出商品名称
                    $wholesalerInfo = Wholesaler::findOne(['id' => $model->wholesaler_id]);
                    return $wholesalerInfo ? $wholesalerInfo->name : '';
                },
            ],
//            'pin_price',
            [
                'attribute' => 'pin_price',
                'label' => '拼团价格（单位:元）',
                'value' => function ($model) {
                    return sprintf("%.2f", $model->pin_price/100);
                },
            ],
            'start_time',
            'end_time',
//            'type',
            [
                'attribute' => 'type',
                'label' => '拼团类型',
                'value' => function ($model) {
                    return $model->type == 1 ? '单点拼团' : '多点拼团';
                },
            ],
            //'strategy',
            'member_num',
//            'continue_pintuan',
            [
                'attribute' => 'continue_pintuan',
                'label' => '超过可继续拼团 ',
                'value' => function ($model) {
                    return $model->continue_pintuan == 1 ? '是' : '否';
                },
            ],
            'sort',
            'create_at',
//            'update_at',
//            'status',
            [
                'attribute' => 'status',
                'label' => '已手动结束',
                'value' => function ($model) {
                    return $model->status == 2 ? '是' : '否';
                },
            ],
//            'del',
            [
                'attribute' => 'del',
                'label' => '已删除',
                'value' => function ($model) {
                    return $model->del == 2 ? '是' : '否';
                },
            ],
            [
                'attribute' => 'self_lifting',
                'label' => '自提点类型',
                'value' => function ($model) {
                    // 查询出是否自定义自提点
                    $store_ids = PintuanActivityStore::find()->select('store_id')->where(['pintuan_activity_id' => $model->id])->column();
                    return empty($store_ids) ? '同供货商配送范围' : '自置自提点';
                },
            ],
            [
                'attribute' => 'self_lifting_position',
                'label' => '自置自提点',
                'value' => function ($model) {
                    // 查询出是否自定义自提点
                    $store_ids = PintuanActivityStore::find()->select('store_id')->where(['pintuan_activity_id' => $model->id])->column();
                    $store_name = '';
                    if (empty($store_ids)) {
                        // 查询出超市的名字并显示在自提点列表中
                        $storeName = \backend\models\Store::find()->select('name')->where(['id' => $store_ids])->column();
                        $store_name = implode(';', $storeName);
                    }
                    return $store_name;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
            [
                'attribute' => 'operation',
                'format' => 'html',
                'label' => '操作',
                'value' => function ($model) {
                    $end = '';
                    if($model->status == 1){
                        $endUrl = \yii\helpers\Url::toRoute('/pintuan-activity/end') . '?id=' . $model->id;
                        $end = "<a href='$endUrl'>结束</a>";
                    }
                    $userUrl = \yii\helpers\Url::toRoute('/pintuan/index') . '?PintuanSearch[pintuan_activity_id]=' . $model->id;
                    $users = "<a href='$userUrl'>查看拼团</a>";
                    return  '    '  . $end  . '    ' .  $users;
                },
            ],
        ],
    ]); ?>
</div>
