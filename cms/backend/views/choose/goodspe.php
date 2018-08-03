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
        <div class="layui-input-inline">
            <select name="third_category_id" id="third_category_id" lay-verify="required" lay-search="">
                <option value="0">选择商品分类</option>
                <?php if ($third_category) { ?>
                    <?php foreach ($third_category as $cat) { ?>
                        <option value="<?php echo $cat['id'];?>" <?php if($cat['id']==$third_category_id) echo ' selected ';?>><?php echo $cat['name'];?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="layui-inline">
        <div class="layui-input-inline">
            <select name="wholesaler_id" id="wholesaler_id" lay-verify="required" lay-search="">
                <option value="0">选择供货商</option>
                <?php if ($wholesaler) { ?>
                      <?php foreach ($wholesaler as $val) { ?>
                        <option value="<?php echo $val['id'];?>" <?php if($val['id']==$wholesaler_id) echo ' selected ';?>><?php echo $val['name'];?></option>
                      <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" value="<?php echo $name;?>" autocomplete="off" placeholder="商品名称">
    </div>
    <div class="layui-btn-group">
        <button type="button" class="layui-btn" id="searchbtn" data-type="reload">搜索</button>
    </div>
    <div class="layui-inline">
        <button type="button" class="layui-btn layui-btn-danger" id="querenbtn" data-type="getCheckData" style="margin-left: 20px;"><i class="layui-icon">&#xe608;</i>确认选择</button>
    </div>
</form>
</div>
<table class="layui-table" id="goodtable"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
   var url = "<?php echo \yii\helpers\Url::toRoute(['choose/getgoodspelist']) ?>";
    layui.use(['table'], function(){
        var table = layui.table;
        //方法级渲染
        table.render({
            elem: '#goodtable'
            ,url: url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id', title: '规格ID', width:80, sort: true, fixed: true}
                ,{field:'name', title: '商品名称', width:400}
                ,{field:'wholesaler_name', title: '供货商', width:300}
                ,{field:'item_detail', title: '规格'}
                ,{field:'price', title: '销售价(元)'}
            ]]
            ,id: 'goodtable'
            ,page: true
            ,limit : 10
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var third_category_id = $('#third_category_id').val();
                var wholesaler_id = $('#wholesaler_id').val();
                var name = $('#name').val();
                table.reload('goodtable', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            name: name,
                            third_category_id: third_category_id,
                            wholesaler_id: wholesaler_id
                        }
                    }
                    ,limit : 10
                });
            },
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('goodtable')
                    ,data = checkStatus.data;

                var jsonData = JSON.stringify(data);
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                parent.getGoods(jsonData);
            }
        };

        $('#searchbtn,#querenbtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>