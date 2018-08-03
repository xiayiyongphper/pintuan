<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '导入打款';
$this->params['breadcrumbs'][] = ['label' => '店铺结算管理', 'url' => ['newindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .myred {
        color:#FF0000;
        font-weight: bold;
    }
</style>
<table class="layui-table">
    <tbody>
    <tr><td style="background-color: green;color:white">温馨提示：</td></tr>
    <tr><td>1、只支持<span class="myred">xlsx格式的excel文件</span>,打款状态只能填写：待打款！</td></tr>
    <tr><td>2、目前<span class="myred">最多只支持5K条记录</span>导入！导入过程中，请耐心等待...</td></tr>
    </tbody>
</table>
<div class="layui-upload">
        <button type="button" class="layui-btn layui-btn-normal" name="imageFile" id="imageFile">选择结算数据文件</button>
        <button type="button" class="layui-btn" id="importbtn">开始导入进行打款</button>
</div>

<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var indexUrl = "<?php echo \yii\helpers\Url::toRoute(['settlement-order/newindex']);?>";
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        var uploadUrl = "<?php echo \yii\helpers\Url::toRoute(['settlement-order/uploadwalletrecords']);?>";
        //选完文件后不自动上传
        upload.render({
            elem: '#imageFile'
            ,url: uploadUrl
            ,method:'post'
            ,accept :'file'
            ,auto: false
            //,multiple: true
            ,bindAction: '#importbtn'
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            }
            ,done: function(res){
                layer.closeAll();
                if(res.code == 0) {
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function(){
                        window.location = indexUrl;
                    }, 1500)
                } else {
                    layer.msg(res.msg, {icon: 5});
                }
            }, error: function(){
            //请求异常回调
           }
        });
    });
</script>