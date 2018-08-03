<?php
/**
 * Created by PhpStorm.
 * User: lqs
 * Date: 2018/5/31 0031
 * Time: 14:51
 */

use yii\widgets\LinkPager;
use backend\models\StoreLogin;

$this->title = '店铺列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 0;
        border-radius: 4px;
    }
</style>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<div class="user-index">

    <p>
        店铺列表
    </p>
    <form action="<?php echo \yii\helpers\Url::toRoute(['store/list']) ?>" method="GET">
        <table class="table" align="right">

            <select name="province" id="province"
                    style="width: 100px;margin-right: 10px;padding-bottom: 2px;padding-top: 2px">
                <option value="0">请选择</option>
                <?php if ($provinces) { ?>
                    <?php foreach ($provinces as $province) { ?>
                        <option value="<?php echo $province['id'] ?>" <?php echo $province['id'] == $p ? "selected" : "" ?>><?php echo $province['name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>

            <select name="city" id="city" style="width: 100px;margin-right: 10px;padding-bottom: 2px;padding-top: 2px">
                <option value="0">请选择</option>
            </select>

            <select name="region" id="region"
                    style="width: 100px;margin-right: 10px;padding-bottom: 2px;padding-top: 2px">
                <option value="0">请选择</option>
            </select>


            <input type="text" name="phone" placeholder="请输入店铺名称/手机号码"
                   style="border:1px solid #369;width:200px;margin-right: 30px" value="<?php echo $phone ?>">
            <button class="btn btn-primary" type="submit" style="width:80px;margin-right: 30px" href="">查询</button>
            <a class="btn" style="width:80px" href="<?php echo \yii\helpers\Url::toRoute(['import/store']) ?>">导入店铺</a>
            </tr>
        </table>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr class="info">
            <th><p class="text-center">店铺ID</p></th>
            <th><p class="text-center">店铺名称</p></th>
            <th><p class="text-center">店主名字</p></th>
            <th><p class="text-center">钱包余额(元)</p></th>
            <th><p class="text-center">小程序二维码</p></th>
            <th width="12%"><p class="text-center">设置为店主的用户</p></th>
            <th><p class="text-center">联系电话</p></th>
            <th><p class="text-center">城市</p></th>
            <th><p class="text-center">区域</p></th>
            <th><p class="text-center">导入时间</p></th>
            <th><p class="text-center">操作</p></th>
        </tr>
        </thead>
        <tobdy>
            <?php if ($res) { ?>
                <?php foreach ($res as $val) { ?>
                    <?php
                        $nickName = StoreLogin::getUserNames($val['id']);
                    ?>
                    <tr>
                        <td><p class="text-center"><?php echo $val['id'] ?></p></td>
                        <td><p class="text-center"><a
                                        href="<?php echo \yii\helpers\Url::toRoute(['store/detail', 'id' => $val['id']]) ?>"><?php echo $val['name'] ?></a>
                            </p></td>
                        <td><p class="text-center"><?php echo $val['owner_user_name'] ?></p></td>
                        <td><p class="text-center" style="color:#EE7600;font-weight: bold;"><?php echo sprintf("%.2f", $val['wallet'] / 100)?></p></td>
                        <td><img src="<?php echo $val['mini_program_qrcode'] ?>" alt="" width="200"></td>
                        <td><p class="text-center"><?php echo $nickName ?></p></td>
                        <td><p class="text-center"><?php echo $val['store_phone'] ?></p></td>
                        <td><p class="text-center"><?php echo $val['city_name'] ?></p></td>
                        <td><p class="text-center"><?php echo $val['district_name'] ?></p></td>
                        <td><p class="text-center"><?php echo $val['created_at'] ?></p></td>
                        <td><p class="text-center">
                                <a href="<?php echo \yii\helpers\Url::toRoute(['store/detail', 'id' => $val['id']]) ?>">详情</a>|
                                <a href="<?php echo \yii\helpers\Url::toRoute(['store/pintuan-info', 'id' => $val['id']]) ?>">乐小拼信息</a>|
                                <a href="<?php echo \yii\helpers\Url::toRoute(['store/commission', 'id' => $val['id']]) ?>">佣金</a>|
                                <a href="<?php echo \yii\helpers\Url::toRoute(['store/wallet-record', 'id' => $val['id']]) ?>">钱包</a>
                            </p></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tobdy>
    </table>
    <table class="table" align="right">
        <tr>

            <td colspan="7" style="border: none;">
                <?php
                echo LinkPager::widget([
                    'pagination' => $pages,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                    'hideOnSinglePage' => false,
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                ]);
                ?>
            </td>
        </tr>
    </table>
</div>

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
        if (res) {
            var len = res.length;
            var html = '';
            var item = [];
            var f_city = '';
            var city = "<?php echo $c;?>";
            html += '<option value="0">请选择</option>';
            for (var i = 0; i < len; i++) {
                item = res[i];
                var selected = '';
                if (city == item.id) {
                    selected = ' selected';
                }
                html += '<option value="' + item.id + '"' + selected + '>' + item.name + '</option>';
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
        var param = {pid: pro};
        var url = "<?php echo \yii\helpers\Url::toRoute(['store/region'])?>";
        request(param, url, fill_citys);
    }

    function get_regions(pro) {
        var param = {pid: pro};
        var url = "<?php echo \yii\helpers\Url::toRoute(['store/region'])?>";
        request(param, url, fill_regions);
    }
    function fill_regions(res) {
        if (res) {
            var len = res.length;
            var html = '';
            var item = [];
            var region = "<?php echo $r;?>";
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
        //注意，这里是重点，重写yii框架自带的分页控件的click事件
        if ($("#province").val() >0) {
            get_citys($("#province").val());
            get_regions($("#city").val())
        }
        //监听省切换
        $("#province").on('change', function () {
            if ( $(this).val() > 0) {
                $('#region').html('<option value="0">选择区</option>');
                get_citys($(this).val());
            } else {
                $('#city').html('<option value="0">选择市</option>');
                $('#region').html('<option value="0">选择区</option>');
            }
        });
        //监听市切换
        $("#city").on('change', function () {
            get_regions($(this).val());
        });
        //监听区域切换
        $("#region").on('change', function () {
        });
    })
</script>
