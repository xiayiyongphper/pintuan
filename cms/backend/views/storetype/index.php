<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '店铺类型列表';
$this->params['breadcrumbs'][] = ['label' => '店铺类型管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<style>
    .container {
        margin-left: 220px;
    }
</style>
    <form class="layui-form">
        <div class="layui-inline" style="margin-left: 20px;">
            <button type="button" id="addbtn" class="layui-btn">创建店铺类型</button>
        </div>
    </form>
<table class="layui-table" id="store_type_table" lay-filter="store_type_table"></table>
<script type="text/html" id="barContent">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detailBtn">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delBtn">删除</a>
</script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['storetype/storetypelist']) ?>";
    var detail_url = "<?php echo \yii\helpers\Url::toRoute(['storetype/update']) ?>";
    var createUrl = "<?php echo \yii\helpers\Url::toRoute(['storetype/create']);?>";

    //删除
    function del(obj)
    {
        var data = obj.data;
        var param = {
            id : data.id
        };
        var delUrl = "<?php echo \yii\helpers\Url::toRoute(['storetype/del']);?>";

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


    layui.use(['table','form'], function(){
        var table = layui.table;
        var form = layui.form;
        //方法级渲染
        table.render({
            elem: '#store_type_table'
            ,url: get_url
            ,cols: [[
                {field:'id', title: '编号', width:100, sort: true, fixed: true}
                ,{field:'name', title: '类型名称', width:350}
                ,{field:'commission_type', title: '佣金类型', width:150}
                ,{field:'commission_val', title: '佣金数值', width:100}
                ,{field:'', title: '操作',toolbar: '#barContent'}
            ]]
            ,id: 'store_type_table'
            ,page: true
            ,limit : 10,
            width:1500,
            loading:true
        });

        //监听工具条
        table.on('tool(store_type_table)', function(obj){
            var data = obj.data;
            var id = data.id;
            if(obj.event === 'detailBtn'){
                //详情
                window.location = detail_url + '?id=' +id;
            } else if (obj.event === 'delBtn') {
                layer.confirm('确定要删除【'+data.name+'】吗？', function(index){
                    del(obj);
                    layer.close(index);
                });
            }
        });


        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var title = $('#title').val();

                table.reload('store_type_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            title: title
                        }
                    }
                    ,limit : 10
                });
            }
        };

        $('#addbtn').on('click', function(){
            window.location = createUrl;
        });
    });
</script>