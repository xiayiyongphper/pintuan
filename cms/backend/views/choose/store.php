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
<form class="layui-form" style="padding: 5px">
    <div class="layui-inline">
        <div class="layui-input-inline">
            <select name="province" id="province" lay-verify="required" lay-search=""  lay-filter="province">
                <option value="0">选择省份</option>
                <?php if ($province) { ?>
                    <?php foreach ($province as $pro) { ?>
                        <option value="<?php echo $pro['code'];?>"><?php echo $pro['name'];?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="layui-inline">
        <div class="layui-input-inline">
            <select name="city" id="city" lay-verify="required" lay-search="" lay-filter="city">
                <option value="0">选择城市</option>
            </select>
        </div>
    </div>
    <div class="layui-inline">
        <div class="layui-input-inline">
            <select name="district" id="district" lay-verify="required" lay-search="">
                <option value="0">选择区/县</option>
            </select>
        </div>
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" value="" autocomplete="off" placeholder="店铺名称">
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="owner_user_name" id="owner_user_name" value="" autocomplete="off" placeholder="店主名字">
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="store_phone" id="store_phone" value="" autocomplete="off" placeholder="联系电话">
    </div>
    <div class="layui-btn-group">
        <button type="button" class="layui-btn" id="searchbtn" data-type="reload">搜索</button>
    </div>
    <div class="layui-inline">
        <button type="button" class="layui-btn layui-btn-danger" id="querenbtn" data-type="getCheckData" style="margin-left: 20px;"><i class="layui-icon">&#xe608;</i>确认选择</button>
    </div>
</form>
<table class="layui-table" id="storeReload" style="width: 100%"></table>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
   var url = "<?php echo \yii\helpers\Url::toRoute(['choose/getstorelist']) ?>";
   var region_url = "<?php echo \yii\helpers\Url::toRoute(['choose/getregionlist']) ?>";

   //填充城市
   function fillRegion(data, id, level)
   {
      var html = '';
      if (level == 2) {
          html +='<option value="0">选择城市</option>';
      } else {
          html +='<option value="0">选择区/县</option>';
      }
      var len = data.length;
      if (len > 0) {
          for (var i=0; i<len; i++) {
              html +='<option value="'+data[i].code+'">'+data[i].name+'</option>';
          }
      }
      $("#"+id).html(html);
   }

    layui.use(['table','form'], function(){
        var table = layui.table;
        var form = layui.form;
        //方法级渲染
        table.render({
            elem: '#storeReload'
            ,url: url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id', title: '店铺ID', width:80, sort: true, fixed: true}
                ,{field:'name', title: '店铺名称', width:150}
                ,{field:'owner_user_name', title: '店主名字', width:200}
                ,{field:'store_phone', title: '联系电话', width:120}
                ,{field:'city_name', title: '城市', width:100}
                ,{field:'address', title: '区域'}
            ]]
            ,id: 'storeReload'
            ,page: true
            ,limit : 10,
            loading:true
        });

        form.on('select(province)', function(data){
            var province = data.value;
            if (province == 0) {
                $("#district").html('<option value="0">选择区/县</option>');
                $("#city").html('<option value="0">选择城市</option>');
                form.render('select');
            } else {
                var param = {
                    code : province
                };
                $.ajax({
                    url:region_url,
                    method:'get',
                    data:param,
                    dataType:'json',
                    success:function(res){
                        var data = res.data;
                        var html = '';
                        html +='<option value="0">选择城市</option>';
                        $("#district").html('<option value="0">选择区/县</option>');

                        var len = data.length;
                        if (len > 0) {
                            for (var i=0; i<len; i++) {
                                html +='<option value="'+data[i].code+'">'+data[i].name+'</option>';
                            }
                        }
                        $("#city").html(html);
                        form.render('select');
                    },
                    error:function (data) {
                    }
                })
                return false;
            }
        });

        form.on('select(city)', function(data){
            var city = data.value;
            if (city == 0) {
                $("#district").html('<option value="0">选择区/县</option>');
                form.render('select');
            } else {
                var param = {
                    code : city
                };
                $.ajax({
                    url:region_url,
                    method:'get',
                    data:param,
                    dataType:'json',
                    success:function(res){
                        var data = res.data;
                        var html = '';
                        html +='<option value="0">选择区域</option>';
                        var len = data.length;
                        if (len > 0) {
                            for (var i=0; i<len; i++) {
                                html +='<option value="'+data[i].code+'">'+data[i].name+'</option>';
                            }
                        }
                        $("#district").html(html);
                        form.render('select');
                    },
                    error:function (data) {
                    }
                })
                return false;
            }
        });

        var $ = layui.$, active = {
            reload: function(){
                //执行重载
                var province = $('#province').val();
                var city = $('#city').val();
                var district = $('#district').val();

                var name = $('#name').val();
                var store_phone = $('#store_phone').val();
                var owner_user_name = $('#owner_user_name').val();

                table.reload('storeReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            name: name,
                            province: province,
                            city: city,
                            district: district,
                            store_phone: store_phone,
                            owner_user_name: owner_user_name,
                        }
                    }
                    ,limit : 10
                });
            },
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('storeReload')
                    ,data = checkStatus.data;
                var jsonData = JSON.stringify(data);
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                parent.getStores(jsonData);
            }
        };

        $('#searchbtn,#querenbtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>