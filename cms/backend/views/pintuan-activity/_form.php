<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

// 判断是否编辑
$operation = isset($this->params['operation']) ? $this->params['operation'] : '';

if ($operation== 'edit') {
    $display = '';
    $product_id = isset($productInfo)? $productInfo->id : 0;
    $product_name = isset($productInfo)? $productInfo->name : '';
    $apiUrl = \yii\helpers\Url::toRoute('pintuan-activity/doupdate');
    $id = $model->id;
} else {
    $display = 'none';
    $product_id = 0;
    $product_name = '';
    $apiUrl = \yii\helpers\Url::toRoute('pintuan-activity/docreate');
    $id = 0;
}
$indexUrl = \yii\helpers\Url::toRoute('pintuan-activity/newindex');

/* @var $this yii\web\View */
/* @var $model app\models\PintuanActivity */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    var apiUrl = "<?php echo $apiUrl;?>";
</script>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .myhide {
        display: none;
    }
</style>
<div class="pintuan-activity-form">
    <?php $form = ActiveForm::begin(['options' => ['id'=>'myform','enctype' => 'multipart/form-data']]); ?>

    <input type="hidden" id="pintuanactivity-id" class="form-control" name="PintuanActivity[id]" value="<?php echo $id;?>" aria-required="true">
    <?= $form->field($model, 'title')->label('标题')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sort')->label('排序')->textInput() ?>

    <div class="form-group">
        <button id="chooseGoodBtn" type="button" class="btn btn-success">
           添加拼团商品
        </button>
    </div>
    <div class="form-group">
        <input type="hidden" value="<?php echo $product_id;?>" id="product_id" name="PintuanActivity[product_id]">
        <table class="layui-table" id="productBox" style="display:<?php echo $display;?>">
            <thead>
            <tr>
                <th>商品ID</th>
                <th>商品名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td id="product_id_td"><?php echo $product_id;?></td><td id="product_name_td"><?php echo $product_name;?></td>
              <td><button type="button" class="btn btn-success" onclick="delguige2(this)">删除</button></td>
            </tr>
            </tbody>
        </table>
        <table class="layui-table" id="specificationBox" style="display:<?php echo $display;?>">
            <thead>
            <tr>
                <th>规格ID</th>
                <th>规格</th>
                <th>库存</th>
                <th>拼团价格(元)</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="goodbox">
                <?php if ($operation== 'edit' && isset($specifications) && $specifications) { ?>
                      <?php foreach ($specifications as $val) { ?>
                        <tr id="spe_tr_<?php echo $val['specification_id'];?>">
                            <td><?php echo $val['specification_id'];?><input type="hidden" name="speIds[]" value="<?php echo $val['specification_id'];?>"></td>
                            <td><?php echo $val['item_detail'];?></td>
                            <td><?php echo $val['qty'];?></td>
                            <td><input class="pintuan_price" type="text" name="pin_prices[]" maxlength="10" value="<?php echo sprintf("%.2f", $val['pin_price'] / 100);?>"></td>
                            <td><button type="button" class="btn btn-success" onclick="delguige(this)">移除</button></td>
                        </tr>
                      <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php $actionID = strtolower(Yii::$app->controller->action->id);?>
    <div style="width: 400px;">
        <div>拼团时间:<span style="color: red"> (一旦新增,不能修改)</span></div>
        <?php if ((!$model->start_time && !$model->end_time) || ($actionID=='copy')) { ?>
            <?= $form->field($model, 'start_time')->label('开始时间')->widget(DateTimePicker::className(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii',
                    'startDate' => '01-Mar-2014 12:00 AM',
                    'todayHighlight' => true,
                ]
            ]);
            ?>

            <?= $form->field($model, 'end_time')->label('结束时间')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii',
                    'startDate' => '01-Mar-2014 12:00 AM',
                    'todayHighlight' => true
                ]
            ]);
            ?>
        <?php } else { ?>
            <?= $form->field($model, 'start_time')->label('开始时间')->textInput(['readOnly' => true]); ?>
            <?= $form->field($model, 'end_time')->label('结束时间')->textInput(['readOnly' => true]); ?>
        <?php } ?>
    </div>

    <!--  默认全部多点拼团 -->
    <?= Html::radioList('PintuanActivity[type]', $model->type ? $model->type : 2, [1 => '单点拼团', 2 => '多点拼团'],['style'=>'display:none;']) ?>
    <?= $form->field($model, 'place_type')->radioList(['1'=>'同店铺配送范围', '2'=>'手动选择自提点']) ?>
    <div class="form-group actStoreBox <?php echo $model->place_type== 2? ' ' : ' myhide ';?>">
        <button id="choosestoreBtn" type="button" class="btn btn-success">
            添加自提点
        </button>
    </div>
    <div class="form-group actStoreBox <?php echo $model->place_type== 2? ' ' : ' myhide ';?>">
        <table class="layui-table">
            <thead>
            <tr>
                <th>店铺ID</th>
                <th>店铺名称</th>
                <th>店主名字</th>
                <th>联系电话</th>
                <th>城市</th>
                <th>地址</th>
                <th>操作</th>
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
                        <td><button class="layui-btn" onclick="delguige(this)">移除</button></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?= Html::textInput('store_name', $this->params['store_name'], ['readOnly' => true, 'id' => 'storeNames', 'class' => 'form-control', 'style' => 'display:none;']) ?>
    <?= Html::hiddenInput('store_id', $this->params['store_id'], ['style' => 'display:none;', 'id' => 'storeIds']) ?>
    <p></p>

    <script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
    <div id="chooseAllGroup" style="margin-top: 20px;"></div>

    <p></p>
    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，拼团图片的规格为：750*350 !</label>
    </div>
    <label class="control-label" for="category-name">拼团图片</label>
    <div class="form-group field-product-cover_picture">
        <?= Html::activeHiddenInput($model, 'cover_picture', ['id' => 'cover_picture']); ?>
        <?php
        echo \kartik\widgets\FileInput::widget([
            'name' => 'PintuanActivity[cover_picture]',
            'options' => [
                'multiple' => true
            ],
            'pluginOptions' => [
                // 异步上传的接口地址设置
                'uploadUrl' => \yii\helpers\Url::to(['/pintuan-activity/image-upload']),
                'uploadAsync' => true,
                // 需要预览的文件格式
                'previewFileType' => 'image',
                // 预览的文件
                'initialPreview' => $model->cover_picture ? $model->cover_picture : '',
                // 需要展示的图片设置，比如图片的宽度等
                'initialPreviewConfig' => $model->cover_picture ? $model->cover_picture : '',
                // 是否展示预览图
                'initialPreviewAsData' => true,
                // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
                'showRemove' => false,
                // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
                'showUpload' => false,
                //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
                'showBrowse' => true,
                // 展示图片区域是否可点击选择多文件
                'browseOnZoneClick' => false,
                // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                'fileActionSettings' => [
                    // 设置具体图片的查看属性为false,默认为true
                    'showZoom' => true,
                    // 设置具体图片的上传属性为true,默认为true
                    'showUpload' => true,
                    // 设置具体图片的移除属性为true,默认为true
                    'showRemove' => true,
                ],
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            //网上很多地方都没详细说明回调触发事件，其实fileupload为上传成功后触发的，三个参数，主要是第二个，有formData，jqXHR以及response参数，上传成功后返回的ajax数据可以在response获取
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                            var url = data.response.files[0].url;
                            var urls = jQuery("#cover_picture").val();
                            if(url){
                                jQuery("#cover_picture").val(url);
                            }
                            console.log(jQuery("#cover_picture").val());
                            console.log(\'File uploaded triggered\');
                            console.log(data);
                            console.log(data.response.files[0].url);
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#cover_picture").val(params.stack.join(";"));
                            }
                            console.log(params.stack.length);
                            console.log("file solrted");
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#cover_picture").val();
                            if(urls.length>0){
                                jQuery("#cover_picture").val("");
                            }
                        }',
                //错误的冗余机制
                'error' => "function (){
				    alert('图片上传失败');
			     }"
            ]
        ]);
        ?>
    </div>
</div>
<?= $form->field($model, 'effective_hours')->label('拼团有效时长')->textInput() ?>
<div>
    <?= $form->field($model, 'member_num')->label('拼团人数')->textInput(['style' => "width:100px;"]) ?>
    <div style="float: left;margin-left: 110px;margin-top: -35px;">
        <label><input type="checkbox" name="PintuanActivity[continue_pintuan]" value="1" checked="checked" disabled="disabled"> 超过可继续拼团</label>
        <input type="hidden" name="PintuanActivity[continue_pintuan]" value="1">
    </div>
</div>
<div class="person_strategy">
    <div style="font-weight:bold;text-align: left;float: none;">
        人数策略<span style="color: red"> (一旦新增,不能修改)</span>:
    </div>
    <div>
        <?= Html::checkbox('group[]', isset($this->params['strategy']['base_member_num']) ? true : false, ['label' => '基础人数', 'value' => 'base_member_num']) ?>
        开团后<?= Html::textInput('strategy[base_member_num][after_start_min]', isset($this->params['strategy']['base_member_num']['after_start_min']) ? $this->params['strategy']['base_member_num']['after_start_min'] : '', ['style' => 'border: 1px solid #ccc;border-radius: 4px;']) ?>
        分钟，<?= Html::textInput('strategy[base_member_num][member_num]', isset($this->params['strategy']['base_member_num']['member_num']) ? $this->params['strategy']['base_member_num']['member_num'] : '', ['style' => 'border: 1px solid #ccc;border-radius: 4px;']) ?>
        人参团(展示给客户)
    </div>
    <div>
        <?= Html::checkbox('group[]', isset($this->params['strategy']['auto_increment']) ? true : false, ['label' => '系统自动增加人数', 'value' => 'auto_increment']) ?>
        结束前<?= Html::textInput('strategy[auto_increment][before_end_min]', isset($this->params['strategy']['auto_increment']['before_end_min']) ? $this->params['strategy']['auto_increment']['before_end_min'] : '', ['style' => 'border: 1px solid #ccc;border-radius: 4px;']) ?>
        分钟，每<?= Html::textInput('strategy[auto_increment][increment_cycle_min]', isset($this->params['strategy']['auto_increment']['increment_cycle_min']) ? $this->params['strategy']['auto_increment']['increment_cycle_min'] : '', ['style' => 'border: 1px solid #ccc;border-radius: 4px;']) ?>
        分钟增加一人(基于真实参团人数)
    </div>
    <div>
        <?= Html::checkbox('group[]', isset($this->params['strategy']['fill_before_end']) ? true : false, ['label' => '保证成团', 'value' => 'fill_before_end']) ?>
        结束前<?= Html::textInput('strategy[fill_before_end][before_end_min]', isset($this->params['strategy']['fill_before_end']['before_end_min']) ? $this->params['strategy']['fill_before_end']['before_end_min'] : '', ['style' => 'border: 1px solid #ccc;border-radius: 4px;']) ?>
        分钟人数补满
    </div>
</div>
<?= Html::radioList('PintuanActivity[status]', $model->status ? $model->status : 1, [1 => '正常', 2 => '结束']) ?>


<div class="form-group">
    <?= Html::Button('提交', ['id'=>'submitbtn', 'type'=>'button','class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>


<!-- 选择商品的弹窗start -->
<?php
$js = <<<JS
    // 若是编辑 人数策略里面的数据不能修改
    var operation = '{$operation}';
    console.log(operation);
    if(operation==='edit' ){
        $('.person_strategy input').each(function() {
            this.disabled = 'disabled';
        })
    }
JS;
$this->registerJs($js);

$js2 = <<<JS

//提交
$("#submitbtn").click(function(){
    //拼团标题
    var title = $("#pintuanactivity-title").val();
    if (!title) {
        layer.msg('请填写拼团活动标题！', {icon: 5});
         return;
    }
    
    var sort = $("#pintuanactivity-sort").val();
    if (!sort) {
        layer.msg('请填写排序！', {icon: 5});
        return;
    }
    
    var speSize = $("#goodbox tr").size();
    if (speSize == 0) {
        layer.msg('请选择拼团商品！', {icon: 5});
        return;
    }
    
    var priceArr = [];
     $("#goodbox tr td .pintuan_price").each(function(){
          var price = $(this).val();
          priceArr.push(price);
      });
     
     var priceLen = priceArr.length;
     for (var i=0; i < priceLen; i++) {
         var price = priceArr[i];
         if (isNaN(price) || price=='') {
             layer.msg('请认真填写拼团价格！', {icon: 5});
                return;
         }
         
         if (price <=0) {
             layer.msg('请认真填写拼团价格！', {icon: 5});
               return;
         }
     }

    var start_time = $("#pintuanactivity-start_time").val();
    if (start_time == '') {
        layer.msg('请填写拼团的开始时间！', {icon: 5});
         return;
    }
    
    var end_time = $("#pintuanactivity-end_time").val();
    if (end_time == '') {
         layer.msg('请填写拼团的结束时间！', {icon: 5});
         return;
    }
    
     var place_type = $("input[type='radio'][name='PintuanActivity[place_type]']:checked").val();
        if (place_type == 2) {
            var selectSize = $("#storebox tr").size();
            if (selectSize == 0) {
                layer.msg('请选择自提点！', {icon: 5});
                return;
            }
        }
    
    var effective_hours = $("#pintuanactivity-effective_hours").val();
    if (effective_hours == '' || isNaN(effective_hours)) {
          layer.msg('请认真填写拼团的有效时长！', {icon: 5});
         return;
    }
    
     var cover_picture = $("#cover_picture").val();
     if (!cover_picture) {
         layer.msg('请上传拼团图片！', {icon: 5});
          return;
     }
     
     var member_num = $("#pintuanactivity-member_num").val();
     if (member_num == '' || parseInt(member_num) <= 0) {
           layer.msg('请正确填写拼团人数！', {icon: 5});
           return;
     }

     //基础人数数据
     if ($("input[type='checkbox'][name='group[]']").eq(0).prop('checked') && !$("input[type='checkbox'][name='group[]']").eq(0).prop('disabled')) {
         var after_start_min = $("input[type='text'][name*='after_start_min']").val();
         if (after_start_min == '' || parseInt(after_start_min) <= 0) {
              layer.msg('请正确填写:基础人数--数据！', {icon: 5});
               return;
         }
         
         var member_num = $("input[type='text'][name*='_num][member_num]']").val();
         if (member_num == '' || parseInt(member_num) <= 0) {
              layer.msg('请正确填写:基础人数--数据！', {icon: 5});
               return;
         }
     }
     
     //系统自动增加人数
     if ($("input[type='checkbox'][name='group[]']").eq(1).prop('checked') && !$("input[type='checkbox'][name='group[]']").eq(0).prop('disabled')) {
         var before_end_min = $("input[type='text'][name*='before_end_min']").val();
         if (before_end_min == '' || parseInt(before_end_min) <= 0) {
              layer.msg('请正确填写:系统自动增加人数--数据！', {icon: 5});
               return;
         }
         
         var increment_cycle_min = $("input[type='text'][name*='increment_cycle_min']").val();
         if (increment_cycle_min == '' || parseInt(increment_cycle_min) <= 0) {
               layer.msg('请正确填写:系统自动增加人数--数据！', {icon: 5});
               return;
         }
     }

     //保证成团
     if ($("input[type='checkbox'][name='group[]']").eq(2).prop('checked') && !$("input[type='checkbox'][name='group[]']").eq(0).prop('disabled')) {
         var before_end_min = $("input[type='text'][name*='before_end_min']").val();
         if (before_end_min == '' || parseInt(before_end_min) <= 0) {
               layer.msg('请正确填写:保证成团--数据！', {icon: 5});          
                return;
         }
     }
     
       var data =  $("#myform").serialize();
       $.post(apiUrl,data,function(result){
               if (result.code == 0) {
                    layer.msg(result.message, {icon: 1}); 
                    setTimeout(function(){
                         window.location = "{$indexUrl}";
                    }, 1200)
               } else {
                   layer.msg(result.message, {icon: 5}); 
               }
       });
});
JS;

$this->registerJs($js2);
//<!-- 选择自提点列表end -->
?>
<script src="./../layer-v3.1.1/layer.js"></script>
<script>
    //删除商品
    function delguige2(obj)
    {
        $("#productBox,#specificationBox").hide();
        $("#product_id").val(0);
        $("#product_id_td").text(0);
        $("#product_name_td").text('');
        $("#goodbox").html('');
    }
    //删除规格
    function delguige(obj)
    {
        $(obj).parent().parent().remove();

        var len = $("#goodbox tr").size();
        if (len == 0) {
            $("#productBox,#specificationBox").hide();
            $("#product_id").val(0);
            $("#product_id_td").text(0);
            $("#product_name_td").text('');
        }
    }
    //选择商品，获取商品规格
    function getGoods(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len >=2) {
                layer.msg('亲，拼团商品只能选择一个喔！', {icon: 5});
                return;
            }

            var oldId = $("#product_id").val();
            var good = dataList[0];

            var speSize = $("#goodbox tr").size();
            if (good.id != oldId && speSize > 0) {
                layer.msg('亲，先把之前的商品删除了，才能添加新的商品哦！', {icon: 5});
                return;
            }

            var url = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/getspecification']);?>";
            var param = {id:good.id};
            $.ajax({
                type: 'get',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                     var speList = res.data;
                     var speLen = speList.length;
                     if (speLen > 0) {
                         $("#productBox,#specificationBox").show();
                         $("#product_id").val(good.id);
                         $("#product_id_td").text(good.id);
                         $("#product_name_td").text(good.name);

                         var html = '';
                         var item = {};
                         for (var i = 0; i < speLen; i++) {
                             item = speList[i];
                             var speid = item.id;
                             var curId = 'spe_tr_' + speid;
                             if ($('#' + curId).size() > 0) {
                                 continue;
                             }
                             html += '<tr id="'+curId+'"><td>'+speid+'<input type="hidden" name="speIds[]" value="'+speid+'"></td><td>'+item.item_detail+'</td><td>'+item.qty+'</td><td><input class="pintuan_price" type="text" maxlength="10" name="pin_prices[]"></td><td><button type="button" class="btn btn-success" onclick="delguige(this)">移除</button></td></tr>';
                         }

                         $("#goodbox").append(html);
                     }
                }
            });
        }
    }
    //选择自提点
    function getStores(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            var html = '';
            for (var i=0; i<len;i++) {
                var curId = 'store_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }
                html += '<tr id="'+curId+'"><td>'+dataList[i].id+'</td><td><input type="hidden" name="storeids[]" value="'+dataList[i].id+'">'+dataList[i].name+'</td><td>'+dataList[i].owner_user_name+'</td><td>'+dataList[i].store_phone+'</td><td>'+dataList[i].city_name+'</td><td>'+dataList[i].address+'</td><td><button class="layui-btn" onclick="delguige(this)">移除</button></td></tr>';
            }

            $("#storebox").append(html);
        }
    }
    $(function () {
        //place_type类型切换
        $("input[name='PintuanActivity[place_type]']").click(function(){
            var index = $(this).val();
            if (index == 2) {
                $(".actStoreBox" ).removeClass('myhide');
            } else {
                $(".actStoreBox").addClass('myhide');
            }
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

        //选择自提点
        var store_url = "<?php echo \yii\helpers\Url::toRoute(['choose/store']);?>";
        $("#choosestoreBtn").click(function(){
            layer.open({
                type: 2,
                title:'选择拼团自提点',
                area: ['70%', '90%'],
                content: store_url
            });
        });
    });
</script>
