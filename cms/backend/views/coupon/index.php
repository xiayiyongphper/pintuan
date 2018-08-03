<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '优惠券列表';
$this->params['breadcrumbs'][] = ['label' => '优惠券管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .container {
        margin-left: 50px;
    }
   .layui-table tbody tr .laytable-cell-1-qrcode {
         width: 200px;
         height: 200px;
         line-height: 200px;
         padding: 0;
    }
   .layui-table tbody tr .laytable-cell-1-id {
       width: 80px;
       height: 200px;
   }
    .layui-table img {
        width: 200px;
        max-width: 200px;
    }

</style>
<div class="layui-card" style="position: relative">
<form class="layui-form">
    <!--1:新人券 2:促销券 3:分享券-->
    <div class="layui-input-inline">
        <select name="coupon_type" id="coupon_type" lay-verify="required" lay-search="" lay-filter="coupon_type">
            <option value="0">优惠券类型</option>
            <option value="1">新人券</option>
            <option value="2">促销券</option>
            <option value="3">分享券</option>
        </select>
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="title" id="title" value="" autocomplete="off" placeholder="优惠券名称">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查询</button>
    </div>
    <div class="layui-inline" style="margin-left: 20px;">
        <button type="button" id="addbtn" class="layui-btn layui-btn-danger"><i class="layui-icon">&#xe608;</i>创建优惠券</button>
    </div>
</form>
</div>
<table class="layui-table" id="salesrule_table" lay-filter="salesrule_table"></table>
<script type="text/html" id="barContent">
    {{#  if(d.status == 1){ }}
      <a class="layui-btn layui-btn-warm layui-btn-xs" lay-data="2" lay-id="{{d.id}}" lay-event="closeBtn">关闭</a>
    {{#  } else { }}
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="openBtn">启用</a>
    {{#  } }}

    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detailBtn">详情</a>
    <a class="layui-btn layui-btn-xs" lay-event="receiveBtn">领取情况</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delBtn">删除</a>
</script>
<script type="text/html" id="statusTpl">
    {{#  if(d.status == 1){ }}
    <span style="color: blue">启用中</span>
    {{#  } else { }}
    <span style="color: red">已关闭</span>
    {{#  } }}
</script>
<script type="text/html" id="qrcodeTpl">
    {{#  if(d.qrcode){ }}
    <img src="{{ d.qrcode }}">
    {{#  } else { }}
    {{#  } }}
</script>
<script type="text/html" id="coupontypeTpl">
    {{#  if(d.coupon_type == 1){ }}
    <span style="color:#339933">新人券</span>
    {{#  } else if (d.coupon_type == 2) { }}
    <span style="color:#CC3333">促销券</span>
    {{#  } else if (d.coupon_type == 3) { }}
    <span style="color:#0000CC">分享券</span>
    {{#  } }}
</script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['coupon/getsalesrules']) ?>";
    var detail_url = "<?php echo \yii\helpers\Url::toRoute(['coupon/update']) ?>";
    var use_url = "<?php echo \yii\helpers\Url::toRoute(['salesruleusage/index']) ?>";
    var createUrl = "<?php echo \yii\helpers\Url::toRoute(['coupon/create']);?>";

    //重新加载页面
    function reloadPage()
    {
        setTimeout(function () {
            window.location.reload();
        }, 1000)
    }

    function initcss()
    {
        $(".layui-table-body .layui-table tr td[data-field='qrcode']").css({'width':'200px','height':'200px','line-height':'200px','padding':'0px'});
        $(".layui-table-body .layui-table tr td[data-field='id']").css({'width':'80px','height':'200px'});
        $(".layui-table-body .layui-table img]").css({'width':'200px','max-width':'200px'});
        $(".layui-table-body .layui-table tr td[data-field='qrcode'] .layui-table-cell]").css({'height':'200px'});
    }
    //删除
    function del(obj)
    {
        var data = obj.data;
        var param = {
            id : data.id
        };
        var delUrl = "<?php echo \yii\helpers\Url::toRoute(['coupon/del']);?>";

        $.ajax({
            type: 'post',
            url: delUrl,
            data: param,
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    layer.msg(res.message, {icon: 1});
                    obj.del();
                    reloadPage();
                } else {
                    layer.msg(res.message, {icon: 5});
                }
            }
        });
     }

    //状态设置
    function setStatus(obj,status)
    {
        var data = obj.data;
        var param = {
            id : data.id,
            status : status
        };

        var setUrl = "<?php echo \yii\helpers\Url::toRoute(['coupon/setstatus']);?>";

        $.ajax({
            type: 'post',
            url: setUrl,
            data: param,
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    layer.msg(res.message, {icon: 1});
                    reloadPage();
                } else {
                    layer.msg(res.message, {icon: 5});
                }
            }
        });
    }

    layui.use(['table','form'], function(){
        var table = layui.table;
        var form = layui.form;
        //方法级渲染
        table.render({
            elem: '#salesrule_table'
            ,url: get_url
            ,cols: [[
                {field:'id', title: 'ID', width:80, sort: true, fixed: true}
                ,{field:'title', title: '优惠券名称', width:350}
                ,{field:'', title: '券类型', width:80,templet: '#coupontypeTpl'}
                ,{field:'qrcode', title: '小程序码', width:200,templet: '#qrcodeTpl'}
                ,{field:'status', title: '状态', width:80, templet: '#statusTpl'}
                ,{field:'discount_amount', title: '优惠券面额', width:100}
                ,{field:'effective_day', title: '领取后失效时间', width:130}
                ,{field:'uses_per_coupon', title: '发放总量', width:100}
                ,{field:'uses_per_customer', title: '每人限制领取', width:120}
                ,{field:'condition', title: '使用条件', width:150}
                ,{field:'', title: '操作',toolbar: '#barContent'}
            ]]
            ,id: 'salesrule_table'
            ,page: true
            ,limit : 5,
            width:1700,
            loading:true
        });

        //监听工具条
        table.on('tool(salesrule_table)', function(obj){
            var data = obj.data;
            var id = data.id;
            if(obj.event === 'openBtn'){
                 //状态开启
                layer.confirm('确定要开启【'+data.title+'】吗？', function(index){
                    setStatus(obj, 1);
                    layer.close(index);
                });
            } else if (obj.event === 'closeBtn') {
                //状态关闭
                layer.confirm('确定要关闭【'+data.title+'】吗？', function(index){
                    setStatus(obj, 2);
                    layer.close(index);
                });
            }else if(obj.event === 'detailBtn'){
                //详情
                window.location = detail_url + '?id=' +id;
            } else if(obj.event === 'receiveBtn'){
                 //领取情况
                window.location = use_url + '?id=' +id;
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
                var title = $('#title').val();
                var coupon_type = $('#coupon_type').val();

                table.reload('salesrule_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            title: title,
                            coupon_type:coupon_type
                        }
                    }
                    ,limit : 5
                    ,done :function(){
                         //重置初始化样式
                        initcss();
                    }
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