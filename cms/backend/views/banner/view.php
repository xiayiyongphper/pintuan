<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Banner */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-view">

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
            <tr class="success"><th colspan="4"><?php echo $bannerMsg;?></th></tr>
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
                        <td><?php echo $val['id'];?></td>
                        <td><?php echo $val['title'];?></td>
                        <?php if ($model->type == 2) { ?>
                            <td><?php echo $val['productName'];?></td>
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
            [
                'attribute' => '图片',
                'format' => ['image', ['width' => '500']],
                'value' => function ($model) {
                    return $model->img_url;
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
