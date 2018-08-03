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
    .layui-table-cell{
        height: 60px;
    }
    .layui-table-cell .layui-form-checkbox[lay-skin=primary], .layui-table-cell .layui-form-radio[lay-skin=primary] {
        top: 25px;
        vertical-align: middle;
    }
</style>
<form class="layui-form">
    <div class="layui-inline">
        <input class="layui-input" name="title" id="title" autocomplete="off" placeholder="专题名称">
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="topicId" id="topicId" autocomplete="off" placeholder="专题ID">
    </div>
    <div class="layui-inline">
        <button type="button" id="searchbtn" class="layui-btn" data-type="reload">搜索</button>
    </div>
    <div class="layui-inline">
        <button type="button" class="layui-btn layui-btn-danger" id="querenbtn" data-type="getCheckData" style="margin-left: 20px;"><i class="layui-icon">&#xe608;</i>确认选择</button>
    </div>
</form>
<table class="layui-table" id="topic_table"></table>
<script type="text/html" id="imgTpl">
    <img width="300" src="{{ d.img_url }}">
</script>
<script src="../../layui/layui.js"></script>
<script>
   var url = "<?php echo \yii\helpers\Url::toRoute(['choose/gettopics']) ?>";
    layui.use(['table'], function(){
        var table = layui.table;
        //方法级渲染
        table.render({
            elem: '#topic_table'
            ,url: url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id', title: '专题ID', width:80,sort: true, fixed: true}
                ,{field:'title', title: '专题名称', width:400}
                ,{field:'sort', title: '权重',width:80}
                ,{field:'', title: '图片',templet:'#imgTpl'}
            ]]
            ,id: 'topicReload'
            ,page: true
            ,limit : 10
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var title = $('#title').val();
                var topicId = $('#topicId').val();
                table.reload('topicReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            title: title,
                            topicId: topicId
                        }
                    }
                    ,limit : 10
                });
            },
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('topicReload')
                    ,data = checkStatus.data;
                var jsonData = JSON.stringify(data);
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                parent.getTopics(jsonData);
            }
        };

        $('#searchbtn,#querenbtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>