<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Topic */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '专题', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="form-group">
        <table class="table table-bordered">
            <thead>
            <tr class="success"><th colspan="4"><?php echo $topicMsg;?></th></tr>
            <tr class="warning">
                <?php if (isset($head) && $head) { ?>
                    <?php foreach ($head as $title) { ?>
                        <th><?php echo $title;?></th>
                    <?php } ?>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($selectData) && $selectData) { ?>
                <?php foreach ($selectData as $val) { ?>
                    <tr class="info">
                        <?php if ($model->type == 1) { ?>
                            <td><?php echo $val['id'];?></td>
                            <td><?php echo $val['name'];?></td>
                            <td><?php echo $val['wholesaler_name'];?></td>
                        <?php } else { ?>
                            <td><?php echo $val['id'];?></td>
                            <td><?php echo $val['title'];?></td>
                            <td><?php echo $val['name'];?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sort',
            'id',
            'title',
            'img_url:image',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    if ($model->type === 1) {
                        return '商品列表';
                    } elseif ($model->type === 2) {
                        return '拼团列表';
                    } else {
                        return '未知';
                    }
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status === 1) {
                        return '启用';
                    } elseif ($model->status === 2) {
                        return '禁用';
                    } else {
                        return '未知';
                    }
                },
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
