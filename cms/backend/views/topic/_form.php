<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Topic */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .myhide {
        display: none;
    }
</style>
<div class="topic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->radioList(['1' => '商品列表', '2' => '拼团列表']) ?>

    <div class="form-group<?php echo $model->type== 1? ' ' : ' myhide ';?>mypopbox" id="btn_box_1">
        <button id="chooseGoodBtn" type="button" class="btn btn-success">
            添加商品
        </button>
    </div>
    <div class="form-group<?php echo $model->type== 2? ' ' : ' myhide ';?>mypopbox" id="btn_box_2">
        <button id="choosePintuanBtn" type="button" class="btn btn-success">
            添加拼团
        </button>
    </div>

    <div class="form-group<?php echo $model->type== 1? ' ' : ' myhide ';?>mypopbox" id="detail_box_1">
        <table class="layui-table" id="productBox" style="display:">
            <thead>
            <tr><th colspan="4" style="background-color: #339999;color:#FFFFFF;">您选择的商品</th></tr>
            <tr>
                <th>商品ID</th>
                <th>商品名称</th>
                <th>供货商</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="goodbox">
            <?php if ($model->type == 1) { ?>
                <?php if (isset($selectData) && $selectData) { ?>
                    <?php foreach ($selectData as $val) { ?>
                        <tr id="good_<?php echo $val['id'];?>">
                            <td><input type="hidden" name="goodids[]" value="<?php echo $val['id'];?>"><?php echo $val['id'];?></td>
                            <td><?php echo $val['name'];?></td>
                            <td><?php echo $val['wholesaler_name'];?></td>
                            <td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="form-group<?php echo $model->type== 2? ' ' : ' myhide ';?>mypopbox" id="detail_box_2">
        <table class="layui-table">
            <thead>
            <tr><th colspan="4" style="background-color: #339999;color:#FFFFFF;">您选择的拼团活动</th></tr>
            <tr>
                <th>拼团活动ID</th>
                <th>活动名称</th>
                <th>商品名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="pintuanbox">
            <?php if ($model->type == 2) { ?>
                <?php if (isset($selectData) && $selectData) { ?>
                    <?php foreach ($selectData as $val) { ?>
                        <tr id="pintuan_<?php echo $val['id'];?>">
                            <td><input type="hidden" name="topicids[]" value="<?php echo $val['id'];?>"><?php echo $val['id'];?></td>
                            <td><?php echo $val['title'];?></td>
                            <td><?php echo $val['name'];?></td>
                            <td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，专题图片的规格为：346*346 !</label>
    </div>
    <div class="form-group">
        <label class="control-label">专题图片</label>
    </div>

    <div class="form-group field-topic-img_url" id="topic_img">
        <?= Html::activeHiddenInput($model, 'img_url', ['id' => 'gallery']); ?>
        <?php
        echo \kartik\file\FileInput::widget([
            'name'          => 'Topic[image]',
            'options'       => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl'            => \yii\helpers\Url::to(['/topic/image-upload']),
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
        <?= Html::submitButton('保存', ['class' => 'btn btn-success', 'onclick' => 'checkForm(this);return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="./../layer-v3.1.1/layer.js"></script>
<script>
    function checkForm(obj) {
        var type = $("input[name='Topic[type]']:checked").val()
        if ($("input[name='Topic[img_url]']").val() == '') {
            alert("请选上传图片");
            return false;
        }

        jQuery(obj).parent().parent().submit();
    }
</script>
<script>
    //填充选中的商品
    function getGoods(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len == 0) {
                return;
            }

            var html = '';
            for (var i = 0; i<len; i++) {
                var curId = 'good_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }
                html += '<tr id="'+curId+'"><td><input type="hidden" name="goodids[]" value="'+dataList[i].id+'">'+dataList[i].id+'</td><td>'+dataList[i].name+'</td><td>'+dataList[i].wholesaler_name+'</td><td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>';
            }
            $("#goodbox").append(html);
        }
    }

    //填充选中的拼团活动
    function getPintuans(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len == 0) {
                return;
            }

            var html = '';
            for (var i = 0; i<len; i++) {
                var curId = 'pintuan_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }

                html += '<tr id="'+curId+'"><td><input type="hidden" name="topicids[]" value="'+dataList[i].id+'">'+dataList[i].id+'</td><td>'+dataList[i].title+'</td><td>'+dataList[i].productName+'</td><td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>';
            }
            $("#pintuanbox").append(html);
        }
    }
    //删除
    function deltr(obj)
    {
        $(obj).parent().parent().remove();
    }

    function checkForm(obj) {
        if (!$("input[name='Topic[sort]']").val()) {
            layer.msg('请填写权重！', {icon: 5});
            return false;
        }

        if (!$("input[name='Topic[title]']").val()) {
            layer.msg('请填写标题！', {icon: 5});
            return false;
        }

        var topicType = $("input[type='radio'][name='Topic[type]']:checked").val();
        if (topicType == 1) {
            var selectSize = $("#goodbox tr").size();
            if (selectSize == 0) {
                layer.msg('请选择商品！', {icon: 5});
                return;
            }
        } else if (topicType == 2) {
            var selectSize = $("#pintuanbox tr").size();
            if (selectSize == 0) {
                layer.msg('请选择拼团活动！', {icon: 5});
                return;
            }
        }

        if ($("input[name='Topic[img_url]']").val() == '') {
            layer.msg('请选上传图片！', {icon: 5});
            return false;
        }
        jQuery(obj).parent().parent().submit();
    }

    //类型切换
    $(function(){
        //类型切换
        $("input[name='Topic[type]']").click(function(){
            var index = $(this).val();
            $(".mypopbox").hide();
            $("#btn_box_" + index).show();
            $("#detail_box_" + index).show();
        });
        //选择商品
        var good_url = "<?php echo \yii\helpers\Url::toRoute(['choose/good']);?>";
        $("#chooseGoodBtn").click(function(){
            layer.open({
                type: 2,
                title:'<span style="color:red;">【选择商品】</span>',
                area: ['70%', '90%'],
                content: good_url
            });
        });

        //选择拼团
        var pintuan_url = "<?php echo \yii\helpers\Url::toRoute(['choose/pintuan']);?>";
        $("#choosePintuanBtn").click(function(){
            layer.open({
                type: 2,
                title:'<span style="color:red;">【选择拼团活动】</span>',
                area: ['70%', '90%'],
                content: pintuan_url
            });
        });
    });
</script>