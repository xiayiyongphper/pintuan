<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
$this->title = '钱包';
$this->params['breadcrumbs'][] = ['label' => '店铺列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = $info->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<div class="layui-container" style="width: 1430px;">
    <div class="layui-row">
        <input type="hidden" id="store_id" name="store_id" value="<?php echo $store_id;?>">
        <div class="layui-col-md4" style="background-color: #EE6A50;width: 300px;height: 80px;text-align: center;padding-top: 30px;color:white;font-size: 16px;margin-right: 20px;">
            钱包总计：<?php echo $total;?>元
        </div>
        <div class="layui-col-md4" style="background-color: #AB82FF;width: 300px;height: 80px;text-align: center;padding-top: 30px;color: white;font-size: 16px;margin-right: 20px;">
            已提现：<?php echo $total2;?>元
        </div>
        <div class="layui-col-md4" style="background-color: #8FBC8F;width: 300px;height: 80px;text-align: center;padding-top: 30px;color: white;font-size: 16px;margin-right: 20px;">
            待提现：<?php echo $total3;?>元
        </div>
    </div>
</div>
<table class="layui-table" id="wallet_record_table" lay-filter="wallet_record_table"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var store_id = "<?php echo $store_id;?>";
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['store/getwalletrecords']) ?>";
    layui.use(['table','form'], function(){
        var table = layui.table;
        var form = layui.form;

        //方法级渲染
        table.render({
            elem: '#wallet_record_table'
            ,url: get_url
            ,cols: [[
                {field:'record_number', title: '流水号', width:250, sort: true, fixed: true}
                ,{field:'create_at', title: '时间', width:180}
                ,{field:'amount', title: '金额', width:120}
                ,{field:'type_label', title: '类型'}
            ]]
            ,id: 'wallet_record_table'
            ,page: true
            ,limit : 20
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
                table.reload('wallet_record_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            store_id:store_id
                        }
                    }
                    ,limit : 20
                });
            }
        };
    });
</script>