<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '商品管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $form = ActiveForm::begin(['action' => Url::toRoute('/product/import'),'options' => ['id'=>'my_import_form', 'enctype' => 'multipart/form-data']]) ?>
    <table class="post_table table table-striped table-bordered" id="post_table">
        <tbody>
        <tr>
            <td>SPU导入</td>
            <td>SKU导入</td>
            <td>SPU导出</td>
            <td>SKU导出</td>
            <td>第三方平台商品导入</td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    <?= Html::fileInput("spu_in",null,['class' => "btn btn-success"]) ?>
                    <input type="hidden" name="good_id" id="good_id">
                    <input type="hidden" name="good_name" id="good_name">
                    <input type="hidden" name="good_category_name" id="good_category_name">
                    <input type="hidden" name="good_wholesaler_name" id="good_wholesaler_name">
                    <input type="hidden" name="good_status" id="good_status">
                    <input type="hidden" name="btn_type" id="btn_type" value="">
                </div>
                <div class="form-group" style="padding-left:5px;">
                    <p data="spu_in_btn" onclick="exportGoods(this)" style="display: inline-block;cursor: pointer;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;text-align: center; background-color: #5cb85c;border-color: #4cae4c;color: #fff;">导入</p>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <?= Html::fileInput("sku_in",null,['class' => "btn btn-success"]) ?>
                </div>
                <div class="form-group" style="padding-left:5px;">
                    <p data="sku_in_btn" onclick="exportGoods(this)" style="display: inline-block;cursor: pointer;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;text-align: center; background-color: #5cb85c;border-color: #4cae4c;color: #fff;">导入</p>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label>导出的时候，请耐心等待</label>
                </div>
                <div class="form-group" style="padding-left:5px;">
                    <p data="spu_out_btn" onclick="exportGoods(this)" style="display: inline-block;cursor: pointer;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;text-align: center; background-color: #5cb85c;border-color: #4cae4c;color: #fff;">导出</p>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <label>导出的时候，请耐心等待</label>
                </div>
                <div class="form-group" style="padding-left:5px;">
                    <p data="sku_out_btn" onclick="exportGoods(this)" style="display: inline-block;cursor: pointer;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;text-align: center; background-color: #5cb85c;border-color: #4cae4c;color: #fff;">导出</p>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <?= Html::fileInput("third_spu_in",null,['class' => "btn btn-success"]) ?>
                </div>
                <div class="form-group" style="padding-left:5px;">
                    <p data="third_spu_in_btn" onclick="exportGoods(this)" style="display: inline-block;cursor: pointer;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;text-align: center; background-color: #5cb85c;border-color: #4cae4c;color: #fff;">导入</p>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <?php ActiveForm::end() ?>
    <p>
        <?= Html::a(Yii::t('app', '新建商品'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            'id',
            'name',
            [
                'label'=>'分类',
                'attribute' => 'category_name',
                'value' => 'category.name',
                'filter' => Html::activeTextInput($searchModel, 'category_name', [
                    'class' => 'form-control'
                ]),
            ],
            [
                'label'=>'供应商',
                'attribute' => 'wholesaler_name',
                'value' => 'wholesaler.name',
                'filter' => Html::activeTextInput($searchModel, 'wholesaler_name', [
                    'class' => 'form-control'
                ]),
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 1) {
                        return "上架";
                    } elseif ($model->status === 2) {
                        return "下架";
                    } else {
                        return "未知";
                    }
                },
            ],
//            'images',
//            'description',
            //'unit',
            //'create_at',
            //'update_at',
//            'third_category_id',
            //'del',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script>
    function exportGoods(obj)
    {
        var btn = $(obj);
        var btnName = btn.attr('data');

        if (btnName == 'spu_out_btn' || btnName == 'sku_out_btn') {
            var id = $('input[type="text"][name="ProductSearch[id]"]').val();
            var name = $('input[type="text"][name="ProductSearch[name]"]').val();
            var category_name = $('input[type="text"][name="ProductSearch[category_name]"]').val();
            var wholesaler_name = $('input[type="text"][name="ProductSearch[wholesaler_name]"]').val();
            var status = $('input[type="text"][name="ProductSearch[status]"]').val();

            if (!id && !name && !category_name && !wholesaler_name) {
                alert('请选择搜索条件！');
                return;
            }

            $("#good_id").val(id);
            $("#good_name").val(name);
            $("#good_category_name").val(category_name);
            $("#good_wholesaler_name").val(wholesaler_name);
            $("#good_status").val(status);
        }

        $("#btn_type").attr('name', btn.attr('data'));
        $("#btn_type").val(btn.attr('data'));

        //提交表单搜索
        $("#my_import_form").submit();
    }
</script>
