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
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .layui-table-cell .layui-form-checkbox[lay-skin=primary], .layui-table-cell .layui-form-radio[lay-skin=primary] {
        top: 5px;
        vertical-align: middle;
    }
</style>
<div class="layui-inline">
<form class="layui-form">
    <div class="layui-inline">
        <input class="layui-input" name="actId" id="actId" autocomplete="off" placeholder="拼团活动ID">
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="productName" id="productName" autocomplete="off" placeholder="商品名称">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">搜索</button>
    </div>
    <div class="layui-inline">
        <button type="button" class="layui-btn layui-btn-danger" id="querenbtn" data-type="getCheckData" style="margin-left: 20px;"><i class="layui-icon">&#xe608;</i>确认选择</button>
    </div>
</form>
</div>
<table class="layui-table" id="pintuan_table"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
   var url = "<?php echo \yii\helpers\Url::toRoute(['choose/getpintuans']) ?>";
    layui.use(['table'], function(){
        var table = layui.table;
        //方法级渲染
        table.render({
            elem: '#pintuan_table'
            ,url: url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id', title: '拼团活动ID', width:130,sort: true, fixed: true}
                ,{field:'title', title: '拼团活动名称', width:400}
                ,{field:'productName', title: '商品名称'}
            ]]
            ,id: 'pintuan_table'
            ,page: true
            ,limit : 10
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var productName = $('#productName').val();
                var actId = $('#actId').val();
                table.reload('pintuan_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            productName: productName,
                            actId: actId
                        }
                    }
                    ,limit : 10
                });
            },
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('pintuan_table')
                    ,data = checkStatus.data;
                //layer.alert(JSON.stringify(data));
                var jsonData = JSON.stringify(data);
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                parent.getPintuans(jsonData);
            }
        };

        $('#searchbtn,#querenbtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>