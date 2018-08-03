<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
$this->title = '供货商结算';
$this->params['breadcrumbs'][] = ['label' => '供货商结算管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .container {
        margin-left: 220px;
    }
</style>
<div class="layui-card" style="position: relative">
<form class="layui-form">
    <div class="layui-inline">
        <!--打款状态 1-未打款 2已打款-->
        <select name="pay_state" id="pay_state" lay-verify="required" lay-search="">
            <option value="0">打款状态</option>
            <option value="2">已打款</option>
            <option value="1">未打款</option>
        </select>
    </div>
    <div class="layui-inline">
    <input type="text" size="35" name="serachdate" id="serachdate" lay-verify="required" placeholder="选择起止时间" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-inline">
        <input class="layui-input" size="20"  name="wholesaler_name" id="wholesaler_name" value="" autocomplete="off" placeholder="供货商名称">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查询</button>
    </div>
    <div class="layui-inline" style="padding-left: 20px;">
        <button type="button" id="batchpaybtn" class="layui-btn layui-btn-danger" data-type="getCheckData">批量打款</button>
    </div>
</form>
</div>
<table class="layui-table" id="settlement_order_table" lay-filter="settlement_order_table"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['settlement-order/getwholesalersettlement']) ?>";
    layui.use(['table','form','laydate'], function(){
        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#serachdate'
            ,type: 'datetime'
            ,range:'~'
        });

        //方法级渲染
        table.render({
            elem: '#settlement_order_table'
            ,url: get_url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'settlement_num', title: '编号', width:120, sort: true, fixed: true}
                ,{field:'wholesaler_name', title: '供货商名称', width:180}
                ,{field:'bank', title: '开户银行', width:200}
                ,{field:'account', title: '银行账号', width:180}
                ,{field:'account_name', title: '开户名称', width:180}
                ,{field:'settlement_amount', title: '提现金额(元)', width:120}
                ,{field:'created_at', title: '提现时间', width:180}
                ,{field:'pay_state_lable', title: '打款状态', width:100}
                ,{field:'pay_time', title: '打款时间'}
            ]]
            ,id: 'settlement_order_table'
            ,page: true
            ,limit : 20,
            width:1500,
            loading:true
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var pay_state = $('#pay_state').val();
                var serachdate = $('#serachdate').val();
                var wholesaler_name = $('#wholesaler_name').val();

                table.reload('settlement_order_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            pay_state: pay_state,
                            serachdate: serachdate,
                            wholesaler_name: wholesaler_name
                        }
                    }
                    ,limit : 20
                });
            },
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('settlement_order_table')
                    ,data = checkStatus.data;
                var jsonData = eval(JSON.stringify(data));
                if (jsonData.length == 0) {
                    layer.msg('请选择要打款的记录！', {icon: 5});
                    return;
                }

                var param = [];

                for (var i in jsonData) {
                    if (jsonData[i].pay_state == 2) {
                        param = [];
                        layer.msg('【'+ jsonData[i].settlement_num + '】这条记录已打款，请重新选择要打款的记录！', {icon: 5});
                        return;
                    }
                    param.push(jsonData[i].id);
                }

                layer.confirm('您确定要打款吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    layer.closeAll();
                    var index = layer.load(1);
                    var postData = {
                        id : param.join(',')
                    };

                    var setUrl = "<?php echo \yii\helpers\Url::toRoute(['settlement-order/paystate']);?>";
                    $.ajax({
                        type: 'post',
                        url: setUrl,
                        data: postData,
                        dataType: "json",
                        success: function (res) {
                            layer.close(index);
                            if (res.code == 0) {
                                layer.msg(res.message, {icon: 1});
                                $("#searchbtn").click();
                            } else {
                                layer.msg(res.message, {icon: 5});
                            }
                        }
                    });
                }, function(){
                });
            }
        };

        $('#searchbtn,#batchpaybtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>