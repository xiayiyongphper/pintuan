<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \backend\models\Product;
use backend\models\PintuanActivityStore;
use backend\models\Wholesaler;

/* @var $this yii\web\View */
/* @var $model app\models\PintuanActivity */

$this->title = '拼团活动详情:' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '拼团列表', 'url' => ['newindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-activity-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认删除这条数据?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="form-group">
        <table class="table table-bordered">
            <thead>
            <tr class="success"><th colspan="4">拼团商品：<?php echo $productName;?></th></tr>
            <tr class="warning">
                <th>规格ID</th>
                <th>规格</th>
                <th>库存</th>
                <th>拼团价格(元)</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($specifications) && $specifications) { ?>
                <?php foreach ($specifications as $val) { ?>
                    <tr class="info">
                        <td><?php echo $val['specification_id'];?></td>
                        <td><?php echo $val['item_detail'];?></td>
                        <td><?php echo $val['qty'];?></td>
                        <td><?php echo sprintf("%.2f", $val['pin_price'] / 100);?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if ($model->place_type == 2 && $storeList) { ?>
    <div class="form-group">
        <table class="table table-bordered">
            <thead>
            <tr class="success"><th colspan="6">拼团活动自提点</th></tr>
            <tr>
                <th>店铺ID</th>
                <th>店铺名称</th>
                <th>店主名字</th>
                <th>联系电话</th>
                <th>城市</th>
                <th>地址</th>
            </tr>
            </thead>
            <tbody id="storebox">
            <?php if (isset($storeList) && $storeList) { ?>
                <?php foreach ($storeList as $store) { ?>
                    <tr id="store_<?php echo $store['store_id'];?>">
                        <td><?php echo $store['store_id'];?></td>
                        <td><input type="hidden" name="storeids[]" value="<?php echo $store['store_id'];?>"><?php echo $store['store_name'];?></td>
                        <td><?php echo $store['owner_user_name'];?></td>
                        <td><?php echo $store['store_phone'];?></td>
                        <td><?php echo $store['city_name'];?></td><td><?php echo $store['address'];?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
   <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'cover_picture',
                'format' => ['image', ['width' => '100', 'height' => '100']],
                'value' => function ($model) {
                    return $model->cover_picture;
                },
            ],
            [
                'attribute' => 'wholesaler_name',
                'label' => '供应商名称',
                'value' => function ($model) {
                    // 根据商品id查出商品名称
                    $wholesalerInfo = Wholesaler::findOne(['id' => $model->wholesaler_id]);
                    return $wholesalerInfo ? $wholesalerInfo->name : '';
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
//            'strategy',
            [
                'attribute' => 'strategy',
                'label' => '人数策略',
                'format' => 'html',
                'value' => function ($model) {
                    // 文字描述人数策略
                    $strategyInfo = json_decode($model->strategy, true);
                    $str = '';
                    if (!empty($strategyInfo)) {
                        if (isset($strategyInfo['base_member_num'])) {
                            $str = '基础人数:开团后<font color="red" style="font-weight: bold;"> ' . $strategyInfo['base_member_num']['after_start_min'] . ' </font>分钟<font color="red" style="font-weight: bold;"> ' . $strategyInfo['base_member_num']['member_num'] . '</font>人参团(展示给用户)<br>';
                        }
                        if (isset($strategyInfo['auto_increment'])) {
                            $str .= '系统自动增加人数:结束前<font color="red" style="font-weight: bold;"> ' . $strategyInfo['auto_increment']['before_end_min'] . '</font>分钟，每<font color="red" style="font-weight: bold;"> ' . $strategyInfo['auto_increment']['increment_cycle_min'] . '</font>分钟增加一人(基于真实参团人数)<br>';
                        }
                        if (isset($strategyInfo['fill_before_end'])) {
                            $str .= '保证成团:结束前<font color="red" style="font-weight: bold;"> ' . $strategyInfo['fill_before_end']['before_end_min'] . '</font>分钟人数补满<br>';
                        }
                    }
                    return $str;
                },
            ],
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
            'update_at',
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
                    return empty($store_ids) ? '同供货商配送范围' : '手动选择自提点';
                },
            ]
        ],
    ]) ?>
</div>
