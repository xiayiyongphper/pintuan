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
    .container {
        margin-left: 220px;
    }
</style>
<blockquote class="site-text layui-elem-quote" style="width:1500px;margin:0;margin-bottom:-20px;border-left: 5px solid #009688">
   <?php echo $name;?>--目前共领取：<?php echo $total;?>张，已经使用：<?php echo $useNum;?>张。
</blockquote>
<input type="hidden" value="<?php echo $id;?>" id="rudeId">
<table class="layui-table" id="usage_table" lay-filter="usage_table"></table>
<script type="text/html" id="barContent">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delBtn">删除</a>
</script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    var myid = "<?php echo $id;?>";
    var get_url = "<?php echo \yii\helpers\Url::toRoute(['salesruleusage/getusage'])?>";
    //删除
    function del(obj)
    {
        var data = obj.data;
        var param = {
            id : data.id
        };
        var delUrl = "<?php echo \yii\helpers\Url::toRoute(['salesruleusage/del']);?>";

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
            elem: '#usage_table'
            ,url: get_url
            ,cols: [[
                {field:'number', title: '序号', width:80, sort: true, fixed: true}
                ,{field:'nick_name', title: '用户昵称', width:150}
                ,{field:'real_name', title: '用户姓名', width:150}
                ,{field:'created_at', title: '领取时间', width:180}
                ,{field:'expiration_date', title: '失效时间', width:180}
                ,{field:'used_at', title: '使用时间', width:180}
                ,{field:'', title: '操作',toolbar: '#barContent'}
            ]]
            ,id: 'usage_table'
            ,page: true
            ,limit : 10,
            width:1500,
            where: {
                key: {
                    id :myid
                }
            },
            loading:true
        });

        //监听工具条
        table.on('tool(usage_table)', function(obj){
            var data = obj.data;
            var id = data.id;
            if (obj.event === 'delBtn') {
                layer.confirm('确定要删除吗？', function(index){
                    del(obj);
                    layer.close(index);
                });
            }
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                table.reload('salesrule_table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            id :myid
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