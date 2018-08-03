<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '钱包余额变更明细';
$this->params['breadcrumbs'][] = ['label' => '钱包余额变更管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .container {
        margin-left: 100px;
        width:1600px;
    }
</style>
<div class="layui-card" style="width:1600px;position: relative">
    <form class="layui-form" style="width:1600px;">
        <div class="layui-input-inline">
            <select name="import_type" id="import_type" lay-verify="required" lay-search="" lay-filter="status">
                <option value="0">全部类型</option>
                <option value="3">奖金转入</option>
                <option value="4">罚金扣除</option>
            </select>
        </div>
        <div class="layui-inline">
            <input type="text" size="35" name="importDate" id="importDate" lay-verify="required" placeholder="起止时间" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-inline">
            <input class="layui-input" size="30"  name="userName" id="userName" value="" autocomplete="off" placeholder="操作人名称">
        </div>
        <div class="layui-inline">
            <input class="layui-input" size="30"  name="storeName" id="storeName" value="" autocomplete="off" placeholder="店铺名称">
        </div>
        <div class="layui-inline">
            <button type="button" id="searchbtn" class="layui-btn" data-type="reload">查 询</button>
        </div>
        <div class="layui-inline" style="margin-left: 25px;">
            <button type="button" id="importbtn" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe608;</i>新增余额变更</button>
        </div>
    </form>
</div>
<script type="text/html" id="importtypeTpl">
    {{#  if(d.type == 3){ }}
    <span style="color:#4876FF">奖金转入</span>
    {{#  } if(d.type == 4) { }}
    <span style="color:#EE2C2C">罚金扣除</span>
    {{#  } }}
</script>
<table class="layui-table" id="wallet_table" lay-filter="wallet_table"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var getUrl = "<?php echo \yii\helpers\Url::toRoute(['amountimport/getrecords']);?>";

    layui.use(['table','form','laydate'], function(){
        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#importDate'
            ,type: 'datetime'
            ,range:'~'
        });

        //方法级渲染
        table.render({
            elem: '#wallet_table'
            ,url: getUrl
            ,cols: [[
                {field:'id', title: 'ID', width:80, sort: true, fixed: true}
                ,{field:'name', title: '变更的店铺', width:250}
                ,{field:'import_type', title: '变更类型', width:120, templet: '#importtypeTpl'}
                ,{field:'before_wallet', title: '变更前的钱包余额(元)', width:170,style:'color:#EE7600'}
                ,{field:'wallet', title: '变更金额(元)', width:160,style:'color:#008B00;font-weight: bold;'}
                ,{field:'after_wallet', title: '变更后的钱包余额(元)', width:170,style:'color:#EE7600'}
                ,{field:'username', title: '操作人', width:150}
                ,{field:'import_ip', title: '操作的IP地址',width:150}
                ,{field:'import_remark', title: '备注', width:250}
                ,{field:'create_at', title: '创建时间'}
            ]]
            ,id: 'wallet_table'
            ,page: true
            ,limit : 20
            ,width:1710,
            loading:true
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var importDate = $('#importDate').val();
                var import_type = $('#import_type').val();
                var userName = $('#userName').val();
                var storeName = $('#storeName').val();

                table.reload('wallet_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            importDate: importDate,
                            import_type: import_type,
                            userName : userName,
                            storeName:storeName
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

        $('#importbtn').on('click', function(){
            var importUrl = "<?php echo \yii\helpers\Url::toRoute(['amountimport/import']);?>";
            window.location = importUrl;
        });
    });
</script>