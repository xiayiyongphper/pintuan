<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .myhide {
        display: none;
    }
</style>
<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->radioList(['1'=>'商品详情', '2'=>'拼团详情', '3'=>'专题']) ?>
    <input type="hidden" id="banner-value" value="<?php echo $model->value;?>" class="form-control" name="Banner[value]" aria-required="true" aria-invalid="true">
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
    <div class="form-group<?php echo $model->type== 3? ' ' : ' myhide ';?>mypopbox" id="btn_box_3">
        <button id="chooseTopicBtn" type="button" class="btn btn-success">
            添加专题
        </button>
    </div>
    <div class="form-group<?php echo $model->type== 1? ' ' : ' myhide ';?>mypopbox" id="detail_box_1">
        <table class="layui-table" id="productBox" style="display:">
            <thead>
            <tr><th colspan="3" style="background-color: #339999;color:#FFFFFF;">您选择的商品</th></tr>
            <tr>
                <th>商品ID</th>
                <th>商品名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="goodbox">
            <?php if ($model->type == 1) { ?>
                <?php if (isset($selectData) && $selectData) { ?>
                    <?php foreach ($selectData as $val) { ?>
                        <tr id="good_<?php echo $val['id'];?>">
                            <td><?php echo $val['id'];?></td>
                            <td><?php echo $val['productName'];?></td>
                            <td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="form-group<?php echo $model->type== 2? ' ' : ' myhide ';?>mypopbox" id="detail_box_2">
        <table class="layui-table" id="productBox" style="display:">
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
                              <td><?php echo $val['id'];?></td>
                              <td><?php echo $val['title'];?></td>
                              <td><?php echo $val['productName'];?></td>
                              <td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>
                        <?php } ?>
                  <?php } ?>
              <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="form-group<?php echo $model->type== 3? ' ' : ' myhide ';?>mypopbox" id="detail_box_3">
        <table class="layui-table" id="productBox" style="display:">
            <thead>
            <tr><th colspan="3" style="background-color: #339999;color:#FFFFFF;">您选择的专题活动</th></tr>
            <tr>
                <th>专题ID</th>
                <th>专题名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="topicbox">
            <?php if ($model->type == 3) { ?>
                <?php if (isset($selectData) && $selectData) { ?>
                    <?php foreach ($selectData as $val) { ?>
                        <tr id="topic_<?php echo $val['id'];?>">
                            <td><?php echo $val['id'];?></td>
                            <td><?php echo $val['title'];?></td>
                            <td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，banner图片的规格为：750*300 !</label>
    </div>
    <div class="form-group">
        <label class="control-label">banner图片</label>
    </div>
    <div class="form-group field-topic-img_url" id="topic_img">
        <?= Html::activeHiddenInput($model, 'img_url', ['id' => 'gallery']); ?>
        <?php
        echo \kartik\file\FileInput::widget([
            'name' => 'Banner[image]',
            'options' => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/banner/image-upload']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' => $p1,
                'initialPreviewConfig' => $p2,
                'initialPreviewAsData' => true,
                'maxFileSize' => 2800,
                'maxFileCount' => 1,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                            var url = data.response.files[0].url;
                            jQuery("#gallery").val(url);
                            console.log(jQuery("#gallery").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filedeleted' => 'function(event, key){
                            jQuery("#gallery").val("");
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <?= $form->field($model, 'status')->radioList(['1'=>'启用', '2'=>'禁用']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick' => 'checkForm(this);return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="./../layer-v3.1.1/layer.js"></script>
<script>
    //填充选中的商品
    function getGoods(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len >=2) {
                layer.msg('亲，只能选择一个商品喔！', {icon: 5});
                return;
            }

            var oldId = $("#banner_value").val();
            var good = dataList[0];

            var speSize = $("#goodbox tr").size();
            if (good.id != oldId && speSize > 0) {
                layer.msg('亲，先把之前的商品删除了，才能添加新的商品哦！', {icon: 5});
                return;
            }

            var html = '<tr id="good_'+good.id+'"><td>'+good.id+'</td><td>'+good.name+'</td><td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>';
            $("#goodbox").html(html);
            $("#banner-value").val(good.id);
        }
    }

    //填充选中的专题活动
    function getTopics(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len >=2) {
                layer.msg('亲，只能选择一个专题活动喔！', {icon: 5});
                return;
            }

            var res = dataList[0];

            var size = $("#topicbox tr").size();
            if (size > 0) {
                layer.msg('亲，先把之前的专题删除了，才能添加新的专题哦！', {icon: 5});
                return;
            }

            var html = '<tr id="topic_'+res.id+'"><td>'+res.id+'</td><td>'+res.title+'</td><td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>';
            $("#topicbox").html(html);
            $("#banner-value").val(res.id);
        }
    }

    //填充选中的拼团活动
    function getPintuans(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len >=2) {
                layer.msg('亲，只能选择一个拼团活动喔！', {icon: 5});
                return;
            }

            var res = dataList[0];

            var size = $("#pintuanbox tr").size();
            if (size > 0) {
                layer.msg('亲，先把之前的拼团活动删除了，才能添加新的活动哦！', {icon: 5});
                return;
            }

            var html = '<tr id="pintuan_'+res.id+'"><td>'+res.id+'</td><td>'+res.title+'</td><td>'+res.productName+'</td><td><button type="button" class="btn btn-success" onclick="deltr(this)">删除</button></td></tr>';
            $("#pintuanbox").html(html);
            $("#banner-value").val(res.id);
        }
    }
    //删除
    function deltr(obj)
    {
        $(obj).parent().parent().remove();
        $("#banner-value").val('');
    }

    function checkForm(obj) {
        if (!$("input[name='Banner[sort]']").val()) {
            layer.msg('请填写权重！', {icon: 5});
            return false;
        }

        if (!$("input[name='Banner[title]']").val()) {
            layer.msg('请填写标题！', {icon: 5});
            return false;
        }

        var bannerType = $("input[type='radio'][name='Banner[type]']:checked").val();
        if (bannerType == 1) {
            var selectSize = $("#goodbox tr").size();
            if (selectSize == 0) {
                layer.msg('请选择商品！', {icon: 5});
                return;
            }
        } else if (bannerType == 2) {
            var selectSize = $("#pintuanbox tr").size();
            if (selectSize == 0) {
                layer.msg('请选择拼团活动！', {icon: 5});
                return;
            }
        } else if (bannerType == 3) {
            var selectSize = $("#topicbox tr").size();
            if (selectSize == 0) {
                layer.msg('请选择专题活动！', {icon: 5});
                return;
            }
        }

        if ($("input[name='Banner[img_url]']").val() == '') {
            layer.msg('请选上传图片！', {icon: 5});
            return false;
        }
        jQuery(obj).parent().parent().submit();
    }

    //banner类型切换
    $(function(){
         //banner类型切换
        $("input[name='Banner[type]']").click(function(){
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
                title:'<span style="color:red;">【温馨提示：只能选择一个商品！】</span>',
                area: ['70%', '90%'],
                content: good_url
            });
        });

        //选择专题
        var topic_url = "<?php echo \yii\helpers\Url::toRoute(['choose/topic']);?>";
        $("#chooseTopicBtn").click(function(){
            layer.open({
                type: 2,
                title:'<span style="color:red;">【温馨提示：只能选择一个专题活动！】</span>',
                area: ['70%', '90%'],
                content: topic_url
            });
        });

        //选择拼团
        var pintuan_url = "<?php echo \yii\helpers\Url::toRoute(['choose/pintuan']);?>";
        $("#choosePintuanBtn").click(function(){
            layer.open({
                type: 2,
                title:'<span style="color:red;">【温馨提示：只能选择一个拼团活动！】</span>',
                area: ['70%', '90%'],
                content: pintuan_url
            });
        });
    });
</script>