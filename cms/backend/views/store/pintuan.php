<?php


/* @var $this yii\web\View */
/* @var $store backend\models\Store */

$this->params['breadcrumbs'][] = ['label' => '店铺列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = $store->name;
$this->params['breadcrumbs'][] = '乐小拼信息';

?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<div class="store-update">


    <?php $form = \yii\widgets\ActiveForm::begin(['action' => 'pintuan-info-save', 'method' => 'post']); ?>


    <?= \yii\helpers\Html::hiddenInput('id', $store->id) ?>

    <?= $form->field($store, 'bank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($store, 'account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($store, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($store, 'group_nickname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($store, 'group_num')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-store-commission_coefficient has-success">
        <label class="control-label" for="store-commission_coefficient">店铺佣金类型</label>
        <select name="Store[commission_id]" id="store-commission_id">
            <option value="0">店铺佣金类型</option>
            <?php if ($store_type_list) { ?>
                <?php foreach ($store_type_list as $stype) { ?>
                    <option value="<?php echo $stype['id'];?>" <?php echo $stype['id']==$store->commission_id? ' selected ' :'';?>><?php echo $stype['name'];?></option>
                <?php }  ?>
            <?php }  ?>
        </select>
        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="store-bank">配送方式</label>
        <label for="delivery_type1"><input type="radio" id="delivery_type1" name="Store[delivery_type]" value="1" <?php echo ($store->delivery_type==1)? ' checked ' :''; ?>>自提</label>
    </div>
    <div class="form-group">
        <label class="control-label">小程序二维码</label>
        <?php if ($store->mini_program_qrcode) { ?>
            <div class="help-block"><img src="<?php echo $store->mini_program_qrcode;?>" width="200" height="200" alt=""></div>
        <?php } else { ?>
            <div class="help-block" style="color:red;">系统尚未生成</div>
        <?php } ?>
    </div>
    <div class="form-group">
        <?= \yii\helpers\Html::label('微信二维码') ?>
        <?= \yii\helpers\Html::activeHiddenInput($store, 'wx_qrcode', ['id' => 'wx_qrcode']); ?>
        <?= \kartik\widgets\FileInput::widget([
            'name' => 'Store[img]',
            'options' => [
                'multiple' => false
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/store/image-upload']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' => $wx_qrcode,
                'initialPreviewConfig' => $wx_qrcode_op,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#wx_qrcode").val();
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
                            jQuery("#wx_qrcode").val(urlArray.join(";"));
                            console.log(jQuery("#wx_qrcode").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#wx_qrcode").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#wx_qrcode").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#wx_qrcode").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= \yii\helpers\Html::label('店主照片') ?>
        <?= \yii\helpers\Html::activeHiddenInput($store, 'owner_user_photo', ['id' => 'owner_user_photo']); ?>
        <?= \kartik\widgets\FileInput::widget([
            'name' => 'Store[img]',
            'options' => [
                'multiple' => false
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/store/image-upload']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' => $owner_user_photo,
                'initialPreviewConfig' => $owner_user_photo_op,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#owner_user_photo").val();
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
                            jQuery("#owner_user_photo").val(urlArray.join(";"));
                            console.log(jQuery("#owner_user_photo").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#owner_user_photo").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#owner_user_photo").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#owner_user_photo").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= \yii\helpers\Html::label('银行卡照片') ?>
        <?= \yii\helpers\Html::activeHiddenInput($store, 'bank_card_photo', ['id' => 'bank_card_photo']); ?>
        <?= \kartik\widgets\FileInput::widget([
            'name' => 'Store[img]',
            'options' => [
                'multiple' => false
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/store/image-upload']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' => $bank_card_photo,
                'initialPreviewConfig' => $bank_card_photo_op,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#bank_card_photo").val();
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
                            jQuery("#bank_card_photo").val(urlArray.join(";"));
                            console.log(jQuery("#bank_card_photo").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#bank_card_photo").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#bank_card_photo").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#bank_card_photo").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <button type="button" id="submitBtn" class="btn btn-success">保存</button>
    </div>

    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="./../layer-v3.1.1/layer.js"></script>
<script>
    var url = "<?php echo \yii\helpers\Url::toRoute(['store/pintuan-info-save']);?>";
    var index_url = "<?php echo \yii\helpers\Url::toRoute(['store/list']);?>";
    $("#submitBtn").click(function(){
        $.ajax({
            type: 'post',
            url: url,
            data: $("form").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    layer.msg(res.message, {icon: 1});
                    setTimeout(function(){
                        window.location = index_url;
                    }, 1200);
                } else {
                    layer.msg(res.message, {icon: 5});
                }
            }
        });
    });
</script>