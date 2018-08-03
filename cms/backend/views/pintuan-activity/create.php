<?php

use yii\helpers\Html;
use app\models\UploadForm;


/* @var $this yii\web\View */
/* @var $model app\models\PintuanActivity */

$this->title = '新建拼团活动';
$this->params['breadcrumbs'][] = ['label' => '拼团活动列表', 'url' => ['newindex']];
$this->params['breadcrumbs'][] = $this->title;

// 自定义参数
$this->params['product_name'] = '';
// 查询出是否自定义自提点
$this->params['choose_position'] = 1;
$this->params['store_id'] = '';
$this->params['store_name'] = '';
$this->params['strategy'] = ['base_member_num' => ['after_start_min' => 0, 'member_num' => 0], 'auto_increment' => ['before_end_min' => 0, 'increment_cycle_min' => 0], 'fill_before_end' => ['before_end_min' => 0]];

?>
<div class="pintuan-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
