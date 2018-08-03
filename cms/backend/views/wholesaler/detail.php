<?php

use yii\helpers\Url;

?>
<link href="/../css/common.css" type="text/css" rel="stylesheet">
<link href="/../css/add.css" type="text/css" rel="stylesheet">
<div class="row relative">
    <label>基础信息</label>
    <?php echo '<a style="margin-left: 20px" href="' . Url::toRoute(['wholesaler/pin', 'id' => $model['id']]) . '">乐小拼信息</a>' ?>
    <div class="addwrap">
        <div class="addl fl">
            <form id="myform">
                <div class="additem">
                    <label>*供货商名称</label>
                    <input type="text" placeholder="" name="Wholesaler[name]" value="<?php echo $model['name']; ?>">
                </div>
                <div class="additem">
                    <label>*联系电话</label>
                    <input type="text" name="Wholesaler[phone]" value="<?php echo $model['phone']; ?>">
                </div>
                <!--
                <div class="additem">
                    <label>客服电话</label>
                    <input type="text" name="Wholesaler[service_phone]" value="<?php echo $model['service_phone']; ?>">
                </div>
                -->
                <div class="additem">
                    <label>所在区域</label>&nbsp;<?php
                    $provinceHtml = '<select style="width: 100px" name="Wholesaler[province]" id="province">';
                    foreach ($provinces as $province) {
                        $provinceHtml .= '<option value=' . $province['code'];
                        if ($model['province'] == $province['code']) {
                            $provinceHtml .= ' selected';
                        }
                        $provinceHtml .= '>' . $province['name'] . '</option>';
                    }
                    $provinceHtml .= '</select>';
                    echo $provinceHtml;
                    ?>
                    <?php
                    $cityHtml = '<select style="width: 100px;margin-left: 10px" name="Wholesaler[city]" id="city">';
                    foreach ($cities as $city) {
                        $cityHtml .= '<option value=' . $city['code'];
                        if ($model['city'] == $city['code']) {
                            $cityHtml .= ' selected';
                        }
                        $cityHtml .= '>' . $city['name'] . '</option>';
                    }
                    $cityHtml .= '</select>';
                    echo $cityHtml;
                    ?>
                    <?php
                    $districtHtml = '<select style="width: 100px;margin-left: 10px" name="Wholesaler[district]" id="districts">';
                    foreach ($districts as $district) {
                        $districtHtml .= '<option value=' . $district['code'];
                        if ($model['district'] == $district['code']) {
                            $districtHtml .= ' selected';
                        }
                        $districtHtml .= '>' . $district['name'] . '</option>';
                    }
                    $districtHtml .= '</select>';
                    echo $districtHtml;
                    ?>
                </div>
                <div class="additem textwrap" style="margin-top: 20px">
                    <label class="ptop">详细地址</label>
                    &nbsp;<textarea name="Wholesaler[store_address]"><?php echo $model['store_address']; ?></textarea>
                </div>
                <div class="additem" style="display: inline">
                    <label>配送范围</label>
                    <input type="text" id="distincts">
                    <button style="margin-left: 10px;padding-top: 5px;padding-bottom: 5px;" id="setting"
                            type="button">
                        配置
                    </button>
                </div>
                <div class="additem">
                    <label>营业状态</label>
                    <label><input type="radio" value="1" name="Wholesaler[status]"  <?php if ($model['status'] ==1) { echo 'checked ';}?>/>正常营业</label>
                    <label><input type="radio" value="1" name="Wholesaler[status]"  <?php if ($model['status'] ==2) { echo 'checked ';}?>/>暂停营业</label>
                </div>
                <input type="hidden" id="id" name="id" value="<?php echo $model['id']; ?>">
                <div class="additem" style="margin-top: 20px;">
                    <button style="width: 200px" type="button" id="submitBtn">保存</button>
                </div>

            </form>
        </div>
    </div>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="/../layer-v3.1.1/layer.js"></script>
<script>
    var wid;
    $(function () {
        wid = $('#id').val();//供货商id
        getWholesalerDistricts();
        //监听省切换
        $("#province").on('change', function () {
            getRegions($(this).val(), 'city', '#city');
        });
        //监听市切换
        $("#city").on('change', function () {
            getRegions($(this).val(), 'district', '#districts');
        });
        //配送范围配置按钮
        $("#setting").click(function () {
            getWholesalerRegions('province', 0);
        });

        //保存按钮
        $("#submitBtn").click(function () {
            var url = "<?php echo Url::toRoute(['wholesaler/update']); ?>";
            var data = $('#myform').serialize();
            $.post(url,
                data,
                function (data, status) {
                    //alert(data.msg);
                    if (data.code == 1) {
                        alert(data.msg);
                    } else {
                        window.location = "<?php echo \yii\helpers\Url::toRoute(['wholesaler/list']) ?>";
                    }
                },
                'json');
        });
    });


    function getRegions(code, type, id) {
        //1、拉取城市
        $.get("/region/regions" + "?c=" + code + "&t=" + type, function (data, status) {
            var json = $.parseJSON(data);
            if (0 == json.code) {
                var regions = json.data;
                var text = "";
                var len = regions.length;
                for (var i = 0; i < len; i++) {
                    var item = regions[i];
                    text += '<option value=' + item.code + '>' + item.name + '</option>';
                }
                $(id).html(text);
                if ('city' == type) {
                    getRegions(regions[0].code, 'district', '#districts');
                }
            }
        });
    }

    function getWholesalerDistricts() {
        var param = new Object();
        param.wid = wid;
        var url = "<?php echo Url::toRoute(['wholesaler-district/districts']); ?>";
        $.post(url,
            param,
            function (data, status) {
                if (0 == data.code) {
                    var len = data.data.length;
                    var distincts = '';
                    for (var index = 0; index < len; index++) {
                        distincts += data.data[index].district_name + "，";
                    }
                    $('#distincts').val(distincts);
                } else {
                    // alert(data.msg);
                }
            },
            'json');
    }

    //供货商区域列表
    function getWholesalerRegions(type, code, refresh) {
        //1、拉取城市
        $.get("/wholesaler-district/regions" + "?c=" + code + "&t=" + type + "&wid=" + wid, function (data, status) {
            var json = $.parseJSON(data);
            if (0 == json.code) {
                var regions = json.data;
                showRegionsDialog(type, code, regions);
                if (refresh) {
                    getWholesalerRegions('city', province);
                }
            }
        });
    }

    var province;//省份
    var city;//城市
    var provinceDialog;//省份弹框
    var cityDialog;//城市弹框
    var districtDialog;//行政区弹框
    function showRegionsDialog(type, code, regions) {
        if (regions) {
            var len = regions.length;
            html = '<table class="table table-bordered" id="';
            html += type;
            html += '">';
            var title = "";
            switch (type) {
                case 'province':
                    title = "选择省份";
                    break;
                case 'city':
                    province = code;
                    title = "选择城市";
                    break;
                case 'district':
                    city = code;
                    title = "选择行政区";
                    break;
            }
            html += '<caption style="text-align: center">' +
                title +
                '</caption>';
            var columns = 3;
            var row = Math.ceil(len / columns);
            for (var i = 0; i < row; i++) {
                html += '<tr>';
                for (var j = 0; j < columns; j++) {
                    var index = i * columns + j;
                    if (index == len) {
                        break;
                    }
                    var region = regions[index];
                    html += '<td width="160px"><span>' + '<input type="checkbox" value="';
                    html += region.code;
                    html += '" ';
                    if (1 == region.sel) {
                        html += ' checked';
                    }
                    if ('district' != type) {
                        html += ' DISABLED';
                    }
                    html += '/>' + region.name + '</span></td>';
                }
                html += '</tr>';
            }
            html += '</table>';
            if ('district' == type) {
                html += '<div style="padding: 10px">' +
                    '<button style="width: 100px;background: #f01414;border-radius: 5px;border: none;padding: 12px 5px; color: #fff;" type="button" id="settingSave">保存' +
                    '</button></div>';
            }

            var dialog = layer.open({
                type: 1,
                title: false,
                skin: 'layui-layer-rim', //加上边框
                area: ['600px'], //宽高
                content: html
            });
            switch (type) {
                case 'province':
                    provinceDialog = dialog;
                    break;
                case 'city':
                    cityDialog = dialog;
                    break;
                case 'district':
                    districtDialog = dialog;
                    break;
            }
            $('#' + type + ' td').click(function () {
                if (type == 'province') {
                    getWholesalerRegions('city', $(this).find('input').val());
                    return;
                }
                if (type == 'city') {
                    getWholesalerRegions('district', $(this).find('input').val());
                    return;
                }
            });
            $("#settingSave").click(function () {
                // layer.close(districtDialog);
                var params = new Array();
                var $checks = $('#' + type + ' input:checkbox:checked');
                $checks.each(function (index, e) {
                    params[index] = $(this).val();
                });
                console.log(params);
                var param = new Object();
                param.p = province;
                param.c = city;
                param.d = params;
                param.wid = wid;
                var url = "<?php echo Url::toRoute(['wholesaler-district/save']); ?>";
                $.post(url,
                    param,
                    function (data, status) {
                        //console.log(data);
                        if (0 == data.code) {
                            layer.close(districtDialog);
                            layer.close(cityDialog);
                            layer.close(provinceDialog);
                            getWholesalerRegions('province', 0, true);
                            getWholesalerDistricts();
                        } else {
                            alert(data.msg);
                        }
                    },
                    'json');
            });
        }
    }
</script>
