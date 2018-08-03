<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ShareConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="share-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'position')->radioList(['1' => '首页', '2' => '详情']) ?>

    <?= $form->field($model, 'type')->radioList(['1' => '图片', '2' => '截屏']) ?>

    <div class="form-group field-shareconfig-img_url" id="shareconfig_img">
        <?= Html::activeHiddenInput($model, 'img_url', ['id' => 'gallery']); ?>
        <?php
        echo \kartik\file\FileInput::widget([
            'name'          => 'ShareConfig[image]',
            'options'       => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl'            => \yii\helpers\Url::to(['/share-config/image-upload']),
                'showUpload'           => false, // hide upload button
                'uploadAsync'          => true,
                'initialPreview'       => $p1,
                'initialPreviewConfig' => $p2,
                'initialPreviewAsData' => true,
                'maxFileSize'          => 2800,
                'maxFileCount'         => 1,
            ],
            'pluginEvents'  => [
                'fileuploaded' => 'function(event, data, previewId, index){
                            var url = data.response.files[0].url;
                            jQuery("#gallery").val(url);
                            console.log(jQuery("#gallery").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filedeleted'  => 'function(event, key){
                            jQuery("#gallery").val("");
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <?= $form->field($model, 'status')->radioList(['1' => '启用', '2' => '禁用']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick' => 'checkForm(this);return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS
    
    changeType();
    $("input[name='ShareConfig[type]']").on('click', function(){changeType()});
    
JS;

$this->registerJs($js);

?>


<script>


    function changeType() {
        var type = $("input[name='ShareConfig[type]']:checked").val()
        if (type == 1) {
            $("#shareconfig_img").show();
        } else {
            $("#shareconfig_img").hide();
        }
    }

    function checkForm(obj) {
        var type = $("input[name='ShareConfig[type]']:checked").val()
        if (type == 1 && $("input[name='ShareConfig[img_url]']").val() == '') {
            alert("请选上传图片");
            return false;
        }

        jQuery(obj).parent().parent().submit();
    }

</script>
