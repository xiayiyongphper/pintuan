<?php

use yii\widgets\LinkPager;
use yii\data\Pagination;
use yii\helpers\Url;

$this->title = '店铺管理';
?>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<div class="row relative" style="height:600px;position:relative;">
    <div class="controls" id="cityDiv" style="margin-bottom: 10px;position: relative">
        <?php
        //省份选择
        $provinceHtml = '<select style="height: 30px" name="province" id="province">';
        $p = 0;
        if (isset($_GET['p'])) {
            $p = $_GET['p'];
        }
        foreach ($provinces as $province) {
            $provinceHtml .= '<option value=' . $province['code'];
            if ($p == $province['code']) {
                $provinceHtml .= ' selected';
            }
            $provinceHtml .= '>' . $province['name'] . '</option>';
        }
        $provinceHtml .= '</select>';
        echo $provinceHtml;
        ?>

        <?php
        //城市选择
        $c = 0;
        if (isset($_GET['c'])) {
            $c = $_GET['c'];
        }
        $cityHtml = '<select style="margin-left: 10px;height: 30px" name="city" id="city">';
        foreach ($cities as $city) {
            $cityHtml .= '<option value=' . $city['code'];
            if ($c == $city['code']) {
                $cityHtml .= ' selected';
            }
            $cityHtml .= '>' . $city['name'] . '</option>';
        }
        $cityHtml .= '</select>';
        echo $cityHtml;
        ?>

        <?php
        //区域选择
        $d = 0;
        if (isset($_GET['d'])) {
            $d = $_GET['d'];
        }
        $districtHtml = '<select style="margin-left: 10px;height: 30px" name="district" id="district">';
        foreach ($districts as $district) {
            $districtHtml .= '<option value=' . $district['code'];
            if ($d == $district['code']) {
                $districtHtml .= ' selected';
            }
            $districtHtml .= '>' . $district['name'] . '</option>';
        }
        $districtHtml .= '</select>';
        echo $districtHtml;
        ?>
        <input id="keyword" class="form-control" style="width: 300px;margin-left:20px;display: inline" type="text"
               placeholder="请输入店铺名称/店主名字/联系电话"
            <?php if (isset($_GET['w']) && $_GET['w']) {
                $word = $_GET['w'];
                echo "value=$word";
            } ?>
               style="margin-left: 20px;height: 30px"/>
        <button class="btn btn-primary" type="button" style="margin-left: 20px;" id="searchStore">查询
        </button>
    </div>

    <?php
    //店铺列表
    if (isset($stores)) {
        $len = count($stores);
        $text = '<table class="table table-bordered">';
        $text .= '<tr class="info">';
        $text .= '<th>' . '选择' . '</th>';
        $text .= '<th>' . '店铺' . '</th>';
        $text .= '<th>' . '省' . '</th>';
        $text .= '<th>' . '城市' . '</th>';
        $text .= '<th>' . '区域' . '</th>';
        $text .= '<th>' . '联系电话' . '</th>';
        $text .= '<th>' . '类型' . '</th>';
        $text .= '<th>' . '操作' . '</th>';
        $text .= '</tr>';
        foreach ($stores as $store) {
            $text .= '<tr>';
            $text .= '<td><input type="checkbox" class="store_one" name=' . $store['name'] . ' value=' . $store['id'] . '></td>';
            $text .= '<td>' . $store['name'] . '</td>';
            $text .= '<td>' . $store['province_name'] . '</td>';
            $text .= '<td>' . $store['city_name'] . '</td>';
            $text .= '<td>' . $store['district_name'] . '</td>';
            $text .= '<td>' . $store['store_phone'] . '</td>';
            $text .= '<td>' . $store['status_label'] . '</td>';
            $url = Url::toRoute(['store/detail', 'id' => $store['id']]);
            $text .= '<td>' . '<a href="' . $url . '">详情</a>' . '</td>';
            $text .= '</tr>';
        }
        $text .= '</table>';
        echo $text;
    }
    ?>
    <div class="text-right" style="position:absolute;  bottom:0px;right: 0px">
        <?php
        if (isset($pages) && 0 < $pages) {
            $pagination = new Pagination(['totalCount' => $pages * 10, 'defaultPageSize' => 10, 'page' => $page]);
            echo LinkPager::widget([
                'pagination' => $pagination,
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                'hideOnSinglePage' => false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '尾页'
            ]);
        }
        ?>
    </div>

</div>
<script>
    $(function () {
        // 翻页渲染 不直接跳转
        $('.pagination li a').on('click', function () {
            var url = $(this).attr("href");
            $.get(url, {},
                function (data) {
                    $('#chooseAllGroup').html(data);
                }
            );
            return false;
        });

        function getUrlParam(name) {
//构造一个含有目标参数的正则表达式对象
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
//匹配目标参数
            var r = window.location.search.substr(1).match(reg);
//返回参数值
            if (r != null) return unescape(r[2]);
            return null;
        }

        /**
         * 搜索店铺
         */
        $('#searchStore').on('click', function () {
            var product_id = $("#product_id").val();
            if(!product_id){
                alert('请先选择商品再搜索自提点!');
                return false;
            }
            var word = $("#keyword").val();
            var province = $("#province").val();
            var city = $("#city").val();
            var district = $("#district").val();
//        window.location = getUrl('/pintuan-activity/list') + '?p=' + province + '&c=' + city + '&d=' + district + '&page=1' + '&w=' + word;
            var url = getUrl('/pintuan-activity/list') + '?p=' + province + '&c=' + city + '&d=' + district + '&page=1' + '&w=' + word + '&product_id=' + product_id;
            $.get(url, {},
                function (data) {
                    $('#chooseAllGroup').html(data);
                }
            );
        });

        function getUrl(path) {
            return path;
        }

        /**
         * 选择自提点事件
         * **/
        $(".store_one").click(function () {
            if (storeNames.value && storeIds.value) {
                storeNames = storeNames.value.split(',');
                storeIds = storeIds.value.split(',');
            } else {
                storeNames = [];
                storeIds = [];
            }

            if (this.checked === true) {
                storeIds.push(this.value);
                storeNames.push(this.name);
            } else {
                storeIds.splice($.inArray(this.value, storeIds), 1);
                storeNames.splice($.inArray(this.name, storeNames), 1);
            }

            $("#storeIds").val(storeIds);
            $("#storeNames").val(storeNames);
        });

        /**
         * 选择自提点 编辑的时候自动选定
         * **/
        if(storeIds.value){
            storeIds = storeIds.value.split(',');
        }else{
            storeIds = [];
        }

        $(".store_one").each(function () {
            if (storeIds.length > 0) {
                if ($.inArray(this.value, storeIds) >= 0) {
                    this.checked = true;
                }
            }
        });

        //监听省切换
        $("#province").on('change', function () {
            var product_id = $("#product_id").val();
            if(!product_id){
                alert('请先选择商品再搜索自提点!');
                return false;
            }
            var word = $("#keyword").val();
//            window.location = getUrl('/pintuan-activity/list') + '?p=' + $(this).val() + '&w=' + word;
            var url = getUrl('/pintuan-activity/list') + '?p=' + $(this).val() + '&w=' + word  + '&product_id=' + product_id;
            $.get(url, {},
                function (data) {
                    $('#chooseAllGroup').html(data);
                }
            );
        });
        //监听市切换
        $("#city").on('change', function () {
            var product_id = $("#product_id").val();
            if(!product_id){
                alert('请先选择商品再搜索自提点!');
                return false;
            }
            var province = getUrlParam('p');
            var word = $("#keyword").val();
//            window.location = getUrl('/pintuan-activity/list') + '?p=' + province + '&c=' + $(this).val() + '&w=' + word;
            var url = getUrl('/pintuan-activity/list') + '?p=' + province + '&c=' + $(this).val() + '&w=' + word + '&product_id=' + product_id;
            $.get(url, {},
                function (data) {
                    $('#chooseAllGroup').html(data);
                }
            );
        });
        //监听区域切换
        $("#district").on('change', function () {
            var product_id = $("#product_id").val();
            if(!product_id){
                alert('请先选择商品再搜索自提点!');
                return false;
            }
            var province = getUrlParam('p');
            var city = getUrlParam('c');
            var word = $("#keyword").val();
//            window.location = getUrl('/pintuan-activity/list') + '?p=' + province + '&c=' + city + '&d=' + $(this).val() + '&page=1' + '&w=' + word;
            var url = getUrl('/pintuan-activity/list') + '?p=' + province + '&c=' + city + '&d=' + $(this).val() + '&page=1' + '&w=' + word + '&product_id=' + product_id;
            $.get(url, {},
                function (data) {
                    $('#chooseAllGroup').html(data);
                }
            );
        });
    })
</script>