<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PintuanUser */

$this->title = $model->nick_name;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-user-view">

    <h1><?= Html::encode('用户详情：' . $this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'用户ID',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->id;
                }

            ],
            [
                'attribute'=>'验证token',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->auth_token;
                }

            ],
            'open_id',
            'union_id',
            'session_key',
            [
                'attribute'=>'微信昵称',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->nick_name;
                }

            ],
            [
                'attribute'=>'性别',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model->gender==1) {
                        return '男';
                    } else if ($model->gender==2) {
                        return '女';
                    } else {
                        return '未知';
                    }
                }

            ],
            [
                'attribute'=>'语言',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->language;
                }

            ],
            [
                'attribute'=>'城市',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->city;
                }

            ],
            [
                'attribute'=>'省份',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->province;
                }

            ],
            [
                'attribute'=>'国家',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->country;
                }

            ],
            [
                'attribute'=>'头像',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->avatar_url,['width' => '120']);
                }

            ],
            [
                'attribute'=>'手机号',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->phone;
                }

            ],
            [
                'attribute'=>'是否已下单',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model->has_order==1) {
                        return '是';
                    } else if ($model->has_order==2) {
                        return '否';
                    }
                }
            ],
            [
                'attribute'=>'是否为店主',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model->own_store_id) {
                         $storeModel = new \backend\models\Store();
                        $storeInfo = $storeModel::findOne($model->own_store_id);
                        return '是，' . $storeInfo->name;
                    } else {
                        return '否';
                    }
                }
            ],
            [
                'attribute'=>'真实姓名',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->real_name;
                }

            ],
            [
                'attribute'=>'生日',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->birthday;
                }
            ],
            [
                'attribute'=>'星座',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->constellation;
                }
            ],
            [
                'attribute'=>'个性签名',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->signature;
                }
            ],
            [
                'attribute'=>'创建时间',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->created_at;
                }

            ],
            [
                'attribute'=>'更新时间',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->updated_at;
                }

            ],
            [
                'attribute'=>'是否删除',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                   if ($model->del == 1) {
                       return '正常';
                   } else {
                       return '已删除';
                   }
                }
            ],
        ],
    ]) ?>

</div>
