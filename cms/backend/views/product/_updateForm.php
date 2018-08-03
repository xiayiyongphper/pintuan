<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;


/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="product-form">

    <?php $form = ActiveForm::begin(['id' => 'formId']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->radioList(['1' => '上架',2 => '下架']) ?>

    <?= $form->field($model, 'sort') ?>

    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，商品图片的规格为：750*750 !</label>
    </div>
    <label class="control-label" for="category-name">商品图片</label>
    <div class="form-group">
      <?= Html::activeHiddenInput($model, 'images', ['id' => 'images']); ?>
        <?= FileInput::widget([
            'name' => 'Product[image]',
            'options' => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/product/image-upload']),
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
                            var urls = jQuery("#images").val();
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
                            jQuery("#images").val(urlArray.join(";"));
                            console.log(jQuery("#images").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#images").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#images").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#images").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>
    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，商品详情的规格为： 750*任意!</label>
    </div>
    <label class="control-label" for="category-name">商品详情</label>
    <div class="form-group">
        <?= Html::activeHiddenInput($model, 'description', ['id' => 'description']); ?>
        <?= FileInput::widget([
            'name' => 'Product[image]',
            'options' => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/product/image-upload2']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' =>$p3,
                'initialPreviewConfig' =>$p4,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#description").val();
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
                            jQuery("#description").val(urlArray.join(";"));
                            console.log(jQuery("#description").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#description").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#description").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#description").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>
    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label">商品分类</label>
        <select id="parent_id" class="form-control" style="width:30%;display: inline-block">
        </select>
        <select id="sencond_id" class="form-control" style="width:30%;display: inline-block">
            <option value="0">请选择</option>
        </select>
        <select name="Product[third_category_id]" id="third_category_id" class="form-control" style="width:30%;display: inline-block">
            <option value="0">请选择</option>
        </select>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script>
         var third_category_id = "<?php echo $model->third_category_id;?>";
         var parent_id = "<?php echo $parent_id;?>";
         var sencond_id = "<?php echo $sencond_id;?>";

        //获取分类
        function get_category(id, curlevel, domId, curId) {
            var id = id || 0;
            var curlevel = curlevel || 1;
            var curId = curId || 0;

            var url = "<?php echo \yii\helpers\Url::toRoute(['category/catelist']) ?>";
            var param = {
                id: id,
                level : curlevel
            };
            $.ajax({
                type: 'get',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    fill_category(res, domId, curId);
                }
            });
        }
        //填充分类
        function fill_category(res, domId, curId) {
            var data = res.results;
            if (data) {
                var len = data.length;
                var html = '';
                var item = [];
                html += '<option value="0">请选择</option>';

                for (var i = 0; i < len; i++) {
                    item = data[i];
                    var selected = '';
                    if (curId == item.id) {
                        selected = ' selected';
                    }
                    html += '<option value="' + item.id + '"' + selected + '>' + item.name + '</option>';
                }
                $('#' + domId).html(html);
            }
        }

        $(function(){
            //初始化分类
            get_category(0, 1, 'parent_id', parent_id);
            get_category(parent_id, 2, 'sencond_id', sencond_id);
            get_category(sencond_id, 3, 'third_category_id', third_category_id);

            //监听一级分类切换
            $("#parent_id").on('change', function () {
                if ( $(this).val() > 0) {
                    $('#third_category_id').html('<option value="0">选择区</option>');
                    get_category($(this).val(),2,'sencond_id');
                } else {
                    $('#third_category_id').html('<option value="0">请选择</option>');
                    $('#sencond_id').html('<option value="0">请选择</option>');
                }
            });
            //监听2级分类切换
            $("#sencond_id").on('change', function () {
                if ( $(this).val() > 0) {
                    get_category($(this).val(), 3, 'third_category_id');
                } else {
                    $('#third_category_id').html('<option value="0">请选择</option>');
                }
            });
        });
    </script>
</div>
