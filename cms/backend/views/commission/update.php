<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '编辑店铺佣金类型';
$this->params['breadcrumbs'][] = ['label' => '店铺佣金类型管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<form class="layui-form" action="" method="post" lay-filter="example">
    <blockquote class="site-text layui-elem-quote" style="margin:0;margin-top:-15px;padding: 10px;border-left: 5px solid #009688">
        编辑店铺佣金类型
    </blockquote>
    <table class="layui-table" style="margin: 0;">
        <tr>
            <td width="15%">佣金类型名称</td>
            <td>
                <input type="hidden" name="id" value="">
                <input type="text" name="name" lay-verify="required" maxlength="30" autocomplete="off" placeholder="请输入佣金类型名称" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="15%">佣金类型</td>
            <td>
                <input type="radio" name="commission_type" value="1" title="佣金系数" lay-filter="commission_type" checked="checked">
                <input type="radio" name="commission_type" value="2" title="现金" lay-filter="commission_type">
            </td>
        </tr>
        <tr>
            <td width="15%">佣金数值<br/>(<span style="color:red">该值不能超过100</span>)</td>
            <td>
                <input type="text" name="commission_val" lay-verify="required|number" maxlength="6" style="width: 150px;display: inline-block;margin-right: 10px;" autocomplete="off" placeholder="请输入佣金数值" class="layui-input">
                <span>元</span>
            </td>
        </tr>
        <tr><td></td><td><button class="layui-btn" lay-submit="" lay-filter="submit">保存</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button></td></tr>
    </table>
</form>
<script src="../../layui/layui.js"></script>
<script>
    layui.use(['form', 'layer'], function(){
        var form = layui.form
            ,layer = layui.layer;

        form.on('radio(commission_type)', function(data){
            if (data.value == 2) {
                $("#commission_val_msg").text('元');
            } else {
                $("#commission_val_msg").text('%');
            }
        });

        //表单初始赋值
        form.val('example', {
            "id": "<?php echo $info['id'];?>"
            ,"name": "<?php echo $info['name'];?>"
            ,"commission_type": "<?php echo $info['commission_type'];?>"
            ,"commission_val": "<?php echo $info['commission_val'];?>"
        });

        //监听提交
        form.on('submit(submit)', function(data){
            var url = "<?php echo \yii\helpers\Url::toRoute(['commission/add']);?>";
            var param = data.field;
            $.ajax({
                type: 'post',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        var index_url = "<?php echo \yii\helpers\Url::toRoute(['commission/index']);?>";
                        layer.msg(res.message, {icon: 1});
                        setTimeout(function(){
                            window.location = index_url;
                        }, 2000);
                    } else {
                        layer.msg(res.message, {icon: 5});
                    }
                }
            });
            return false;
        });
    });
</script>