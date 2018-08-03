<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
$this->title = '编辑活动';
$this->params['breadcrumbs'][] = ['label' => '新人活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<form class="layui-form" action="" method="post" lay-filter="example">
    <blockquote class="site-text layui-elem-quote" style="margin:0;margin-top:-15px;padding: 10px;border-left: 5px solid #009688">
        编辑新人活动
    </blockquote>
    <table class="layui-table" style="margin: 0;">
        <tr>
            <td width="8%">活动名称</td>
            <td colspan="3">
                <input type="hidden" name="id" value="">
                <input type="text" name="act_name" lay-verify="required" maxlength="30" autocomplete="off" placeholder="请输入活动名称" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="10%">起止时间</td>
            <td>
                <input type="text" name="date" id="date1" lay-verify="required" placeholder="点击选择起止时间" autocomplete="off" class="layui-input">
            </td>
            <td width="10%">活动状态</td>
            <td colspan="3">
                <input type="radio" name="status" value="1" title="开启" checked="checked">
                <input type="radio" name="status" value="2" title="关闭">
            </td>
        </tr>
        <tr>
            <td width="10%">城市</td>
            <td colspan="3">
                <div class="layui-input-inline">
                    <select name="province" id="province" lay-verify="required" lay-search=""  lay-filter="province">
                        <option value="0">选择省份</option>
                        <?php if ($provinceList) { ?>
                            <?php foreach ($provinceList as $pro) { ?>
                                <option value="<?php echo $pro['code'];?>"><?php echo $pro['name'];?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="city" id="city" lay-verify="required" lay-search="" lay-filter="city">
                            <option value="0">选择城市/辖区</option>
                            <?php if ($cityList) { ?>
                                <?php foreach ($cityList as $cy) { ?>
                                    <option value="<?php echo $cy['code'];?>"><?php echo $cy['name'];?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="8%">自提点选择</td>
            <td colspan="3">
                <input type="radio" name="place_type" value="1" lay-filter="place_type" title="同供货商配送范围" checked="checked">
                <input type="radio" name="place_type" value="2" lay-filter="place_type" title="手动选择自提点">
            </td>
        </tr>
        <tr id="zitidian_box" style="display:<?php echo $info->place_type==2? '' : 'none';?>;">
            <td width="10%">
                活动自提点
            </td>
            <td colspan="3">
                <button id="chooseStoreBtn" type="button" class="layui-btn layui-btn-normal" lay-filter="chooseStoreBtn">
                    <i class="layui-icon">&#xe608;</i> 添加活动自提点
                </button>
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>店铺ID</th>
                        <th>店铺名称</th>
                        <th>店主名字</th>
                        <th>联系电话</th>
                        <th>城市</th>
                        <th>地址</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="storebox">
                       <?php if ($storeList) { ?>
                             <?php foreach ($storeList as $store) { ?>
                               <tr id="store_<?php echo $store['store_id'];?>">
                                   <td><?php echo $store['store_id'];?></td>
                                   <td><input type="hidden" name="storeids[]" value="<?php echo $store['store_id'];?>"><?php echo $store['store_name'];?></td>
                                   <td><?php echo $store['owner_user_name'];?></td>
                                   <td><?php echo $store['store_phone'];?></td>
                                   <td><?php echo $store['city_name'];?></td><td><?php echo $store['address'];?></td>
                                   <td><button class="layui-btn" onclick="delguige(this)">移除</button></td>
                               </tr>
                             <?php } ?>
                       <?php } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="10%">
                活动商品
            </td>
            <td colspan="3">
                <button id="chooseGoodBtn" type="button" class="layui-btn layui-btn-warm" lay-filter="chooseGoodBtn">
                    <i class="layui-icon">&#xe608;</i> 添加活动商品
                </button>
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>规格ID</th>
                        <th>商品名称</th>
                        <th>供应商</th>
                        <th>规格</th>
                        <th>售价(元)</th>
                        <th>新人价(元)</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="goodbox">
                    <?php if ($goodList) { ?>
                        <?php foreach ($goodList as $good) { ?>
                            <tr id="prod_<?php echo $good['spec_id'];?>">
                                <td><?php echo $good['spec_id'];?></td>
                                <td>
                                    <input type="hidden" name="speids[]" value="<?php echo $good['spec_id'];?>">
                                    <input type="hidden" name="wholesaler_ids[]" value="<?php echo $good['wholesaler_id'];?>">
                                    <input type="hidden" name="prodids[]" value="<?php echo $good['product_id'];?>"><?php echo $good['pro_name'];?></td>
                                <td><?php echo $good['wholesaler_name'];?></td>
                                <td><?php echo json_encode(json_decode($good['item_detail'],true),JSON_UNESCAPED_UNICODE); ?></td>
                                <td><?php echo sprintf("%.2f", $good['spe_price'] /100);?></td>
                                <td><input type="text" name="prices[]" value="<?php echo sprintf("%.2f", $good['price'] /100);?>" lay-verify="required|number" maxlength="10" autocomplete="off" class="layui-input"></td>
                                <td><button class="layui-btn" onclick="delguige(this)">移除</button></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr><td></td><td colspan="3"><button class="layui-btn" lay-submit="" lay-filter="submit">保存</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button></td></tr>
    </table>
</form>
<script src="../../layui/layui.js"></script>
<script>
    var good_url = "<?php echo \yii\helpers\Url::toRoute(['choose/goodspe']);?>";
    var store_url = "<?php echo \yii\helpers\Url::toRoute(['choose/store']);?>";
    function delguige(obj)
    {
        $(obj).parent().parent().remove();
    }
    function getGoods(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var priceInput = '<td><input type="text" name="prices[]" lay-verify="required|number" maxlength="6" autocomplete="off" placeholder="0.00" class="layui-input"></td>';
            var len = dataList.length;
            var html = '';
            for (var i=0; i<len;i++) {

                var curId = 'prod_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }
                html += '<tr id="'+curId+'"><td>'+dataList[i].id+'</td><td><input type="hidden" name="speids[]" value="'+dataList[i].id+'"><input type="hidden" name="wholesaler_ids[]" value="'+dataList[i].wholesaler_id+'"><input type="hidden" name="prodids[]" value="'+dataList[i].product_id+'">'+dataList[i].name+'</td><td>'+dataList[i].wholesaler_name+'</td><td>'+dataList[i].item_detail+'</td><td>'+dataList[i].price+'</td>'+priceInput+'<td><button class="layui-btn" onclick="delguige(this)">移除</button></td></tr>';
            }
            $("#goodbox").append(html);
        }
    }

    function getStores(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            var html = '';
            for (var i=0; i<len;i++) {
                var curId = 'store_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }
                html += '<tr id="'+curId+'"><td>'+dataList[i].id+'</td><td><input type="hidden" name="storeids[]" value="'+dataList[i].id+'">'+dataList[i].name+'</td><td>'+dataList[i].owner_user_name+'</td><td>'+dataList[i].store_phone+'</td><td>'+dataList[i].city_name+'</td><td>'+dataList[i].address+'</td><td><button class="layui-btn" onclick="delguige(this)">移除</button></td></tr>';
            }

            $("#storebox").append(html);
        }
    }

    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        //表单初始赋值
        form.val('example', {
            "id": "<?php echo $info['id'];?>"
            ,"act_name": "<?php echo $info['act_name'];?>"
            ,"date":"<?php echo $info['start_at'];?> ~ <?php echo $info['end_at'];?>"
            ,"province":"<?php echo $provinceCode;?>"
            ,"city":"<?php echo $cityCode;?>"
            ,"place_type": "<?php echo $info['place_type'];?>"
            ,"province": "<?php echo $info['province'];?>"
            ,"city": "<?php echo $info['city'];?>"
            ,"status": "<?php echo $info['status'];?>"
        });

        //日期
        laydate.render({
            elem: '#date1'
            ,type: 'datetime'
            ,range:'~'
        });

        //监听自提点的选择
        form.on('radio(place_type)', function(data){
            if (data.value == 2) {
                $("#zitidian_box").show();
            } else {
                $("#zitidian_box").hide();
            }
        });

        //监听提交
        form.on('submit(submit)', function(data){
            if (data.field.province == 0) {
                layer.msg('请选择省分！', {icon: 5});
                $('#province').focus();
                return false;
            }

            if (data.field.city == 0) {
                layer.msg('请选择城市！', {icon: 5});
                $('#city').focus();
                return false;
            }

            if (data.field.place_type == 2) {
                var storeLen = $('#storebox').find('tr').size();
                if (storeLen == 0) {
                    layer.msg('请添加活动自提点！', {icon: 5});
                    $('#chooseStoreBtn').focus();
                    return false;
                }
            }

            var goodsLen = $('#goodbox').find('tr').size();
            if (goodsLen == 0) {
                layer.msg('请添加活动商品！', {icon: 5});
                $('#chooseGoodBtn').focus();
                return false;
            }

            var url = "<?php echo \yii\helpers\Url::toRoute(['specialarea/add']);?>";
            var param = data.field;
            $.ajax({
                type: 'post',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        var index_url = "<?php echo \yii\helpers\Url::toRoute(['specialarea/index']);?>";
                        layer.msg(res.message, {icon: 1});
                        setTimeout(function(){
                            window.location = index_url;
                        }, 2000);
                    } else {
                        layer.msg(res.message, {icon: 5});
                    }
                }
            });
            return false;
        });

        form.on('select(province)', function(data){
            var province = data.value;
            if (province == 0) {
                $("#city").html('<option value="0">城市</option>');
                form.render('select');
            } else {
                var param = {
                    code : province
                };
                var region_url = "<?php echo \yii\helpers\Url::toRoute(['choose/getregionlist']) ?>";
                $.ajax({
                    url:region_url,
                    method:'get',
                    data:param,
                    dataType:'json',
                    success:function(res){
                        var data = res.data;
                        var html = '';
                        html +='<option value="0">城市</option>';

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

        //选择商品
        $("#chooseGoodBtn").click(function(){
            layer.open({
                type: 2,
                title:'选择活动商品',
                area: ['70%', '90%'],
                content: good_url
            });
            return false;
        });

        //选择自提点
        $("#chooseStoreBtn").click(function(){
            layer.open({
                type: 2,
                title:'选择活动自提点',
                area: ['70%', '90%'],
                content: store_url
            });
            return false;
        });
    });
</script>