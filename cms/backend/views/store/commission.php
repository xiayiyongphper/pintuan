<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
$this->title = '店铺佣金';
$this->params['breadcrumbs'][] = ['label' => '店铺列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = $info->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<div class="layui-card" style="position: relative">
    <form class="layui-form">
        <div class="layui-inline">
            <input type="text" size="35" name="serachdate" id="serachdate" lay-verify="required" placeholder="选择起止时间" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-inline">
            <select name="status" id="status" lay-verify="required" lay-search="">
                <option value="0">全部佣金</option>
                <option value="1">已转入钱包</option>
                <option value="2">待转入钱包</option>
            </select>
        </div>
        <div class="layui-inline">
            <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查询</button>
        </div>
    </form>
</div>
<div class="layui-container" style="width: 1430px;">
<div class="layui-row">
    <div class="layui-col-md4" style="background-color: #EE6A50;width: 300px;height: 80px;text-align: center;padding-top: 30px;color:white;font-size: 16px;margin-right: 20px;">
        佣金总计：<?php echo $total1;?>元
    </div>
    <div class="layui-col-md4" style="background-color: #AB82FF;width: 300px;height: 80px;text-align: center;padding-top: 30px;color: white;font-size: 16px;margin-right: 20px;">
        已转入钱包：<?php echo $total2;?>元
    </div>
    <div class="layui-col-md4" style="background-color: #8FBC8F;width: 300px;height: 80px;text-align: center;padding-top: 30px;color: white;font-size: 16px;margin-right: 20px;">
        待转入钱包：<?php echo $total3;?>元
    </div>
</div>
</div>
<table class="layui-table" id="commission_record_table" lay-filter="commission_record_table"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var store_id = "<?php echo $store_id;?>";
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['store/getcommissions']) ?>";

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
            elem: '#commission_record_table'
            ,url: get_url
            ,cols: [[
                {field:'order_number', title: '订单编号', width:250, sort: true, fixed: true}
                ,{field:'name', title: '下单用户', width:180}
                ,{field:'delivery_type', title: '配送方式', width:100}
                ,{field:'delivery_type', title: '核销时间', width:180}
                ,{field:'order_amount', title: '订单金额(元)', width:150}
                ,{field:'amount', title: '佣金金额(元)', width:150}
                ,{field:'status_label', title: '佣金状态', width:150}
                ,{field:'transfer_at', title: '转入钱包时间',width:180}
            ]]
            ,id: 'commission_record_table'
            ,page: true
            ,limit : 20
            ,width:1430
            ,where: {
                key: {
                    store_id:store_id
                }
            }
            ,loading:true
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var serachdate = $('#serachdate').val();
                var status = $('#status').val();

                table.reload('commission_record_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            store_id:store_id,
                            serachdate: serachdate,
                            status: status
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
    });
</script>