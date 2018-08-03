<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
$this->title = '订单列表';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .container {
        margin-left: 100px;
    }
</style>
<div class="layui-card" style="position: relative">
<form class="layui-form">
    <div class="layui-input-inline">
        <select name="status" id="status" lay-verify="required" lay-search="" lay-filter="status">
            <option value="0">全部订单</option>
            <option value="1">未支付</option>
            <option value="2">已支付</option>
            <option value="3">已发货</option>
            <option value="4">已到货</option>
            <option value="5">已确认收货</option>
            <option value="6">已取消</option>
        </select>
    </div>
    <div class="layui-inline">
    <input type="text" size="35" name="orderDate" id="orderDate" lay-verify="required" placeholder="下单起止时间" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="30"  name="orderNumber" id="orderNumber" value="" autocomplete="off" placeholder="订单编号">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="30"  name="storeName" id="storeName" value="" autocomplete="off" placeholder="自提点">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="30"  name="userName" id="userName" value="" autocomplete="off" placeholder="用户昵称">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查 询</button>
    </div>
    <div class="layui-inline" style="margin-left: 415px;">
        <button type="button" id="deliverbtn" class="layui-btn layui-btn-normal">导出配送单据</button>
    </div>
    <div class="layui-inline">
        <button type="button" id="financebtn" class="layui-btn layui-btn-danger">导出财务核对单据</button>
    </div>
</form>
</div>
<table class="layui-table" id="order_table" lay-filter="order_table"></table>
<script type="text/html" id="barContent">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="viewBtn">查看</a>
</script>
<script type="text/html" id="statusTpl">
    {{#  if(d.status == 1){ }}
    <span style="color:#383838">未支付</span>
    {{#  } else if (d.status == 2) { }}
    <span style="color:#436EEE">已支付</span>
    {{#  } else if (d.status == 3) { }}
    <span style="color:#698B22">已发货</span>
    {{#  } else if (d.status == 4) { }}
    <span style="color:#00CD66">已到货</span>
    {{#  } else if (d.status == 5) { }}
    <span style="color:#836FFF">已确认收货</span>
    {{#  } else if (d.status == 6) { }}
    <span style="color:#CD0000">已取消</span>
    {{#  } }}
</script>
<script type="text/html" id="amountTpl">
    <span style="color:#FF8247">{{= d.amount }}</span>
</script>
<script type="text/html" id="discountamountTpl">
    <span style="color:#FF8247">{{= d.discount_amount }}</span>
</script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['order/getorders']);?>";
    var detail_url = "<?php echo \yii\helpers\Url::toRoute(['order/view']);?>";

    var deliver_url = "<?php echo \yii\helpers\Url::toRoute(['orderexport/index']);?>";
    var finance_url = "<?php echo \yii\helpers\Url::toRoute(['orderexport/finance']);?>";

    layui.use(['table','form','laydate'], function(){
        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#orderDate'
            ,type: 'datetime'
            ,range:'~'
        });

        //方法级渲染
        table.render({
            elem: '#order_table'
            ,url: get_url
            ,cols: [[
                {field:'id', title: '订单ID', width:80, sort: true, fixed: true}
                ,{field:'order_number', title: '订单编号', width:230}
                ,{field:'create_at', title: '下单时间', width:180}
                ,{field:'amount', title: '订单金额(元)', width:120,templet: '#amountTpl'}
                ,{field:'discount_amount', title: '优惠金额(元)', width:120,templet: '#discountamountTpl'}
                ,{field:'pay_at', title: '支付时间', width:180}
                ,{field:'store_name', title: '自提点', width:250}
                ,{field:'user_info', title: '用户ID/用户昵称', width:180}
                ,{field:'status', title: '状态', width:120, templet: '#statusTpl'}
                ,{field:'', title: '操作',toolbar: '#barContent'}
            ]]
            ,id: 'order_table'
            ,page: true
            ,limit : 10
            ,width:1600,
            loading:true
        });

        //监听工具条
        table.on('tool(order_table)', function(obj){
            var data = obj.data;
            var id = data.id;
            if (obj.event ==='viewBtn') {
                //详情
                window.location = detail_url + '?id=' +id;
            }
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var orderDate = $('#orderDate').val();
                var orderNumber = $('#orderNumber').val();
                var userName = $('#userName').val();
                var storeName = $('#storeName').val();
                var status = $('#status').val();

                table.reload('order_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            orderDate: orderDate,
                            orderNumber: orderNumber,
                            userName : userName,
                            storeName:storeName,
                            status:status
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

        $('#deliverbtn').on('click', function(){
            window.location = deliver_url;
        });

        $('#financebtn').on('click', function(){
            window.location = finance_url;
        });
    });
</script>