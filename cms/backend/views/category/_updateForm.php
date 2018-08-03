<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，分类图片的规格为：146*146 !目前只支持一张图片！</label>
    </div>
    <div class="form-group">
        <label class="control-label">分类图片</label>
    </div>

    <div class="form-group">
        <?= Html::activeHiddenInput($model, 'img', ['id' => 'img']); ?>
        <?= FileInput::widget([
            'name' => 'Category[image]',
            'options' => [
                'multiple' => false
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/category/image-upload']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' => $p1,
                'initialPreviewConfig' => $p2,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#img").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                if(urlArray.length>0){
                                    urlArray.push(url);
                                }else{
                                    urlArray.push(url);
                                }
                            }else{
                                urlArray.push(url);
                            }
                            jQuery("#img").val(urlArray.join(";"));
                            console.log(jQuery("#img").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#img").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#img").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#img").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
