<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '接龙列表';
$this->params['breadcrumbs'][] = ['label' => '接龙活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .container {
        margin-left: 80px;
    }
</style>
<div class="layui-card" style="position: relative">
<form class="layui-form">
    <div class="layui-inline">
    <input type="text" size="40" name="act_date" id="act_date" lay-verify="required" placeholder="选择起止时间" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="30"  name="title" id="title" value="" autocomplete="off" placeholder="活动名称">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="30"  name="good_name" id="good_name" value="" autocomplete="off" placeholder="商品名称">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查询</button>
    </div>
    <div class="layui-inline" style="margin-left: 20px;">
        <button type="button" id="addbtn" class="layui-btn layui-btn-danger"><i class="layui-icon">&#xe608;</i>新增接龙</button>
    </div>
</form>
</div>
<table class="layui-table" id="act_table" lay-filter="act_table"></table>
<script type="text/html" id="barContent">
    {{#  if(d.status == 1){ }}
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-data="2" lay-id="{{d.id}}" lay-event="closeBtn">手动结束</a>
    {{#  } else { }}
    {{#  } }}
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detailBtn">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delBtn">删除</a>
</script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/getactivitys']) ?>";
    var detail_url = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/update']) ?>";
    var createUrl = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/create']);?>";


    //状态设置
    function setStatus(obj,status)
    {
        var data = obj.data;
        var param = {
            id : data.id,
            status : status
        };

        var setUrl = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/setstatus']);?>";
        $.ajax({
            type: 'post',
            url: setUrl,
            data: param,
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    layer.msg(res.message, {icon: 1});
                     $("#searchbtn").click();
                } else {
                    layer.msg(res.message, {icon: 5});
                }
            }
        });
    }

    //删除
    function del(obj)
    {
        var data = obj.data;
        var param = {
            id : data.id
        };
        var delUrl = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/del']);?>";

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
                {field:'id', title: '接龙ID', width:80, sort: true, fixed: true}
                ,{field:'title', title: '标题', width:250}
                ,{field:'name', title: '商品名称', width:250}
                ,{field:'item_detail', title: '规格', width:300}
                ,{field:'activity_price', title: '接龙价', width:120}
                ,{field:'create_at', title: '创建时间', width:160}
                ,{field:'start_time', title: '开始时间', width:160}
                ,{field:'end_time', title: '结束时间', width:160}
                ,{field:'operate_label', title: '状态', width:80}
                ,{field:'', title: '操作',toolbar: '#barContent'}
            ]]
            ,id: 'act_table'
            ,page: true
            ,limit : 20,
            width:1800,
            loading:true
        });

        //监听工具条
        table.on('tool(act_table)', function(obj){
            var data = obj.data;
            var id = data.id;
            if(obj.event === 'openBtn'){
                //状态开启
                layer.confirm('确定要手动开启【'+data.title+'】吗？', function(index){
                    setStatus(obj, 1);
                    layer.close(index);
                });
            } else if (obj.event === 'closeBtn') {
                //状态关闭
                layer.confirm('确定要手动结束【'+data.title+'】吗？', function(index){
                    setStatus(obj, 2);
                    layer.close(index);
                });
            }else if(obj.event === 'detailBtn'){
                //详情
                window.location = detail_url + '?id=' +id;
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
                var title = $('#title').val();
                var good_name = $('#good_name').val();

                table.reload('act_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            act_date: act_date,
                            good_name: good_name,
                            title: title
                        }
                    }
                    ,limit : 20
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