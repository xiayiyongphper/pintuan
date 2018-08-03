<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
?>
<p><label>批量导入店铺</label></p>
<p><label>1、原来的店铺：lelai_slim_customer中的表: le_customers</label></p>
<p><label>2、新的店铺：pintuan_wholesaler中的表: store</label></p>
<p><label>3、填写规则：le_customers主键id1，le_customers主键id2</label></p>
<p><label>4、比如：1245,1378,1790(英文逗号)</label></p>
<div class="user-index">
    <textarea id="ids" cols="150" rows="10"></textarea>
    <button id="importBtn">批量导入</button>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script>
    <?php $url = \yii\helpers\Url::toRoute(['import/import-store'])?>
    $(function(){
        $('#importBtn').click(function(){
            var ids = $("#ids").val();
            var param = {ids:ids};
            $.ajax({
                type: "post",
                url: "<?php echo $url;?>",
                data: param,
                dataType: "json",
                success: function(data){
                     alert(data.msg);
                }
            });
        });
    });
</script>