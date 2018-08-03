<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
$this->title = '拼团活动列表';
$this->params['breadcrumbs'][] = ['label' => '拼团活动管理', 'url' => ['newindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .layui-table-cell{
        height: 60px;
    }
    .container {
        margin-left: 20px;
    }
</style>
<div class="layui-card" style="position: relative">
<form class="layui-form">
    <div class="layui-inline">
    <input type="text" size="35" name="act_date" id="act_date" lay-verify="required" placeholder="创建起止时间" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="20"  name="title" id="title" value="" autocomplete="off" placeholder="活动名称">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="30"  name="productName" id="productName" value="" autocomplete="off" placeholder="商品名称">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查询</button>
    </div>
    <div class="layui-inline" style="margin-left: 20px;">
        <button type="button" id="addbtn" class="layui-btn layui-btn-danger"><i class="layui-icon">&#xe608;</i> 创建拼团活动</button>
    </div>
</form>
</div>
<table class="layui-table" id="act_table" lay-filter="act_table"></table>
<script type="text/html" id="barContent">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="viewBtn">查看</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detailBtn">修改</a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="endBtn">结束</a>
    <a class="layui-btn layui-btn-xs" lay-event="pinBtn">查看拼团</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delBtn">删除</a>
</script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script type="text/html" id="imgTpl">
    <img width="150" src="{{ d.cover_picture }}">
</script>
<script>
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/getpintuanactivitys']);?>";
    var detail_url = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/view']);?>";
    var update_url = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/update']);?>";
    var createUrl = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/create']);?>";

    var end_url     = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/end']);?>";
    var pintuan_url = "<?php echo \yii\helpers\Url::toRoute(['pintuan/index']);?>";

    //删除
    function del(obj)
    {
        var data = obj.data;
        var param = {
            id : data.id
        };
        var delUrl = "<?php echo \yii\helpers\Url::toRoute(['pintuan-activity/delpintuan']);?>";

        $.ajax({
            type: 'post',
            url: delUrl,
            data: param,
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    layer.msg(res.message, {icon: 1});
                    obj.del();
                } else {
                    layer.msg(res.message, {icon: 5});
                }
            }
        });
     }

    layui.use(['table','form','laydate'], function(){
        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#act_date'
            ,type: 'datetime'
            ,range:'~'
        });

        //方法级渲染
        table.render({
            elem: '#act_table'
            ,url: get_url
            ,cols: [[
                {field:'id', title: '活动ID', width:80, sort: true, fixed: true}
                ,{field:'title', title: '活动名称', width:350}
                ,{field:'', title: '图片',width:150, templet:'#imgTpl'}
                ,{field:'name', title: '商品名称', width:150}
                ,{field:'wholesalerName', title: '供应商名称', width:150}
                ,{field:'create_at', title: '创建时间', width:160}
                ,{field:'start_time', title: '开始时间', width:160}
                ,{field:'end_time', title: '结束时间', width:160}
                ,{field:'status_lable', title: '状态', width:80}
                ,{field:'', title: '操作',toolbar: '#barContent'}
            ]]
            ,id: 'act_table'
            ,page: true
            ,limit : 10,
            width:1800,
            loading:true
        });

        //监听工具条
        table.on('tool(act_table)', function(obj){
            var data = obj.data;
            var id = data.id;
            if(obj.event === 'detailBtn'){
                //编辑
                window.location = update_url + '?id=' +id;
            } else if (obj.event ==='viewBtn') {
                //详情
                window.location = detail_url + '?id=' +id;
            } else if (obj.event ==='endBtn') {
                 //结束
                window.location = end_url + '?id=' +id;
            } else if (obj.event ==='pinBtn') {
                //查看拼团
                window.location = pintuan_url + '?PintuanSearch[pintuan_activity_id]=' +id;
            } else if (obj.event === 'delBtn') {
                layer.confirm('确定要删除【'+data.title+'】吗？', function(index){
                    del(obj);
                    layer.close(index);
                });
            }
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var act_date = $('#act_date').val();
                var productName = $('#productName').val();
                var title = $('#title').val();

                table.reload('act_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            act_date: act_date,
                            productName: productName,
                            title: title
                        }
                    }
                    ,limit : 10
                });
            }
        };

        $('#searchbtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        $('#addbtn').on('click', function(){
            window.location = createUrl;
        });
    });
</script>