<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => '店铺列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = $res['name'];
$this->params['breadcrumbs'][] = '店铺详情';
?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=2LvL4Y1qZCSGAMU52RPXggVu"></script>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>

<div class="user-index">

    <form id="formId" class="form-horizontal">
        <fieldset>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_host">店铺名称</label>
                <div class="col-sm-4">
                    <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken ?>">
                    <input type="hidden" name="id" value="<?php echo $res['id'] ?>">
                    <input class="form-control" name="name" id="name" type="text" style="color:black;"
                           value="<?php echo $res['name'] ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_username">手机号码</label>
                <div class="col-sm-4">
                    <input class="form-control" name="store_phone" id="store_phone" type="text" style="color:black;"
                           value="<?php echo $res['store_phone'] ?>"/>
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_username">省/市/县</label>
                <table class="table" align="right" class="col-sm-4">

                    <select name="province" id="province"
                            style="width: 100px;margin-right: 10px;padding-bottom: 2px;padding-top: 2px;margin-left: 15px;">
                        <option value="0">请选择</option>
                        <?php if ($provinces) { ?>
                            <?php foreach ($provinces as $province) { ?>
                                <option value="<?php echo $province['id'] ?>" <?php echo $province['id'] == $province_id ? "selected" : "" ?>><?php echo $province['name'] ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>

                    <select name="city" id="city"
                            style="width: 100px;margin-right: 10px;padding-bottom: 2px;padding-top: 2px">
                        <option value="0">选择城市</option>
                    </select>

                    <select name="district" id="region"
                            style="width: 100px;margin-right: 10px;padding-bottom: 2px;padding-top: 2px">
                        <option value="0">选择地区</option>
                    </select>
                    </tr>
                </table>

            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_password">申请/通过时间</label>
                <div class="col-sm-4">
                    <label class="control-label"
                           for="inputEmail"><?php echo date('Y-m-d h:m', strtotime($res['created_at'])) . ' / ' . date('Y-m-d h:m', strtotime($res['apply_at'])) ?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_password">地址</label>
                <div class="col-sm-4">
                    <input class="form-control" name="address" id="address" type="text" style="color:black;"
                           value="<?php echo $res['address'] ?>"/>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <label class="col-sm-2 control-label" for="ds_password"></label>
            <label>请设置店铺正确位置,以便定位(可以点击地图选取或光标离开店铺地址框后自动获取)</label>
        </fieldset>
        <fieldset>
            <label class="col-sm-2 control-label" for="ds_password"></label>
            <div id="allmap" class="col-sm-8" style="height: 400px"></div>
        </fieldset>

        <fieldset style="margin-top: 20px">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_host">详细地址</label>
                <div class="col-sm-4">
                    <input class="form-control" name="detail_address" id="detail_address" type="text" style="color:black;"
                           value="<?php echo $res['detail_address'] ?>"/>
                </div>
                <label class="col-sm-2 control-label" for="ds_name">客户类型</label>
                <div class="col-sm-4">
                    <select name="type" id="disabledSelect" class="form-control">
                        <option value="1" <?php echo $res['type'] == 1 ? "selected" : ""; ?>>便利店</option>
                        <option value="2" <?php echo $res['type'] == 2 ? "selected" : ""; ?>>餐饮店</option>
                        <option value="3" <?php echo $res['type'] == 3 ? "selected" : ""; ?>>烟酒店</option>
                        <option value="4" <?php echo $res['type'] == 4 ? "selected" : ""; ?>>批零店</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_host">在店时段</label>
                <div class="col-sm-4">
                    <input class="form-control" name="open_time_range" id="open_time_range" type="text"
                           value="<?php echo $res['open_time_range'] ?>"/>
                </div>
                <label class="col-sm-2 control-label" for="ds_name">营业执照号</label>
                <div class="col-sm-4">
                    <input class="form-control" name="business_license_no" id="business_license_no" type="text"
                           value="<?php echo $res['business_license_no'] ?>"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_host">营业执照</label>
                <div class="col-sm-4">
                    <?= Html::activeHiddenInput($res, 'business_license_img', ['id' => 'business_license_img']); ?>
                    <?= FileInput::widget([
                        'name' => 'Store[business_license_img]',
                        'options' => [
                            'multiple' =>false
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['store/image-upload2','name'=>'business_license_img']),
                            'showUpload' => false, // hide upload button
                            'uploadAsync' => true,
                            'initialPreview' => $p1,
                            'initialPreviewConfig' => $p2,
                            'initialPreviewAsData' => true,
                            'overwriteInitial' => false,
                            'maxFileSize' => 2800,
                        ],
                        'pluginEvents' => [
                            'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
//                            var urls = jQuery("#business_license_img").val();
//                            var urlArray = [];
//                            if(urls.length>0){
//                                urlArray = urls.split(";");
//                                if(urlArray.length>0){
//                                    urlArray.push(url);
//                                }else{
//                                    urlArray.push(url);
//                                }
//                            }else{
//                                urlArray.push(url);
//                            }
                            //jQuery("#business_license_img").val(urlArray.join(";"));
                            jQuery("#business_license_img").val(url);
                            console.log(jQuery("#business_license_img").val());
                            console.log(\'File uploaded triggered\');
                        }',
                            'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#business_license_img").val(params.stack.join(";"));
                            }
                        }',
                            'filedeleted' => 'function(event, key){
                            var urls = jQuery("#business_license_img").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#business_license_img").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
                        ]
                    ]);
                    ?>
                </div>
                <label class="col-sm-2 control-label" for="ds_host">店铺正面照</label>
                <div class="col-sm-4">
                    <?= Html::activeHiddenInput($res, 'store_front_img', ['id' => 'store_front_img']); ?>
                    <?= FileInput::widget([
                        'name' => 'Store[store_front_img]',
                        'options' => [
                            'multiple' =>false
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['store/image-upload2','name'=>'store_front_img']),
                            'showUpload' => false, // hide upload button
                            'uploadAsync' => true,
                            'initialPreview' => $p3,
                            'initialPreviewConfig' => $p4,
                            'initialPreviewAsData' => true,
                            'overwriteInitial' => false,
                            'maxFileSize' => 2800,
                        ],
                        'pluginEvents' => [
                            'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
//                            var urls = jQuery("#store_front_img").val();
//                            var urlArray = [];
//                            if(urls.length>0){
//                                urlArray = urls.split(";");
//                                if(urlArray.length>0){
//                                    urlArray.push(url);
//                                }else{
//                                    urlArray.push(url);
//                                }
//                            }else{
//                                urlArray.push(url);
//                            }
                            jQuery("#store_front_img").val(url);
                            console.log(jQuery("#store_front_img").val());
                            console.log(\'File uploaded triggered\');
                        }',
                            'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#store_front_img").val(params.stack.join(";"));
                            }
                        }',
                            'filedeleted' => 'function(event, key){
                            var urls = jQuery("#store_front_img").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#store_front_img").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="ds_name">状态</label>
                <div class="col-sm-3">
                    <select name="status" id="disabledSelect" class="form-control">
                        <option value="0" <?php echo $res['status'] == 0 ? "selected" : ""; ?>>未审核</option>
                        <option value="1" <?php echo $res['status'] == 1 ? "selected" : ""; ?>>审核通过</option>
                        <option value="2" <?php echo $res['status'] == 2 ? "selected" : ""; ?>>审核不通过</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="text-align: center">
                <button class="btn btn-primary" type="button" style="width:80px;" id="saveBtn">保存</button>
            </div>
    </form>
</div>
<script type="text/javascript">
    function jsSelectItemByValue(id, objItemText) {
        var objSelect = document.getElementById(id);
        for (var i = 0; i < objSelect.options.length; i++) {

            if (objSelect.options[i].text == objItemText) {
                objSelect.options[i].selected = true;
                break;
            }
        }
    }
    // 百度地图API功能
    var map = new BMap.Map("allmap");    // 创建Map实例
    map.centerAndZoom(new BMap.Point(<?php echo $res['lng'] ?>,<?php echo $res['lat'] ?>), 14);  // 初始化地图,设置中心点坐标和地图级别
    //添加地图类型控件
    map.addControl(new BMap.MapTypeControl({
        mapTypes: [
            BMAP_NORMAL_MAP,
            BMAP_HYBRID_MAP
        ]
    }));

    var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
    var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
    //添加控件和比例尺
    map.addControl(top_left_control);
    map.addControl(top_left_navigation);
    map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
    var geoc = new BMap.Geocoder();
    map.addEventListener("click", function (e) {
        map.clearOverlays();
        var point = new BMap.Point(e.point.lng, e.point.lat);
        var marker = new BMap.Marker(point);  // 创建标注
        map.addOverlay(marker);               // 将标注添加到地图中

        var pt = e.point;
        geoc.getLocation(pt, function (rs) {
            var addComp = rs.addressComponents;
            //console.log(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);

            if (addComp.province != $("#province option:selected").text()) {
                jsSelectItemByValue("province", addComp.province);
                get_citys($("#province").val());
            }
            if (addComp.city != $("#city option:selected").text()) {
                setTimeout(function () {
                    jsSelectItemByValue("city", addComp.city);
                    get_regions($("#city").val());
                }, 300)
            }

            $("#address").val(addComp.street + addComp.streetNumber);

            setTimeout(function () {
                jsSelectItemByValue("region", addComp.district);
            }, 500);

        });
    });
</script>
<script>
    function getUrlParam(name) {
//构造一个含有目标参数的正则表达式对象
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
//匹配目标参数
        var r = window.location.search.substr(1).match(reg);
//返回参数值
        if (r != null) return unescape(r[2]);
        return null;
    }

    //公用的请求方法
    function request(param, url, func) {
        $.ajax({
            type: 'get',
            url: url,
            data: param,
            dataType: "json",
            success: function (res) {
                func(res);
            }
        });
    }
    function fill_citys(res) {
        if (res && res.length > 0) {
            var len = res.length;
            var html = '';
            var item = [];
            var city = "<?php echo $city_id;?>";
            html += ' <option value="0"> 请选择</option>';
            var f_city = res[0].id;
            for (var i = 0; i < len; i++) {
                item = res[i];
                var selected = '';
                if (city == item.id) {
                    selected = ' selected';
                }
                html += '<option value= "' + item.id + '"' + selected + '>' + item.name + '</option>';
                if (i == 0) {
                    f_city = item.id;
                }
            }
            $('#city').html(html);
            if (city) {
                f_city = city;
            }
            get_regions(f_city);
        }
    }
    function get_citys(pro) {
        var param = {
                pid: pro
            };
        var url = "<?php echo \yii\helpers\Url::toRoute(['store/region'])?>";
        request(param, url, fill_citys);
    }

    function get_regions(pro) {
        var param = {
                pid: pro
            };
        var url = "<?php echo \yii\helpers\Url::toRoute(['store/region'])?>";
        request(param, url, fill_regions);
    }
    function fill_regions(res) {
        if (res) {
            var len = res.length;
            var html = '';
            var item = [];
            var region = "<?php echo $region_id;?>";
            html += '<option value="0">请选择</option>';
            for (var i = 0; i < len; i++) {
                item = res[i];
                var selected = '';
                if (region == item.id) {
                    selected = ' selected';
                }
                html += '<option value="' + item.id + '"' + selected + '>' + item.name + '</option>';
            }

            $('#region').html(html);
        }
    }

    $(function () {
        get_citys($("#province").val());
        //监听省切换
        $("#province").on('change', function () {
            $('#region').html('<option value="0">选择区</option>');
            get_citys($(this).val());
        });
        //监听市切换
        $("#city").on('change', function () {
            get_regions($(this).val());
        });
        //监听区域切换
        $("#region").on('change', function () {
        });

        //保存
        $('#saveBtn').on('click', function() {
            var formData = $('#formId').serializeArray();
            var url = "<?php echo \yii\helpers\Url::toRoute(['store/store-save'])?>";
            $.ajax({
                url: url,
                method: 'post',
                data: formData,
                dataType:'json',
                success: function(data) {
                    if(data.status == 0){
                        //成功
                        var redict_url = "<?php echo \yii\helpers\Url::toRoute(['store/list'])?>";
                        window.location = redict_url;
                    } else {
                        //失败
                        alert(data.msg);
                    }
                }
            })
        })
    })
</script>
