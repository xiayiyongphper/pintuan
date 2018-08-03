<?php

use yii\widgets\LinkPager;
use yii\data\Pagination;
use yii\helpers\Url;

$this->title = '供货商管理';
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
        <input id="keyword" class="form-control" style="width: 200px;margin-left:20px;display: inline" type="text"
               placeholder="请输入供货商名称/联系电话"
            <?php if (isset($_GET['w']) && $_GET['w']) {
                $word = $_GET['w'];
                echo "value=$word";
            } ?>
               style="margin-left: 20px;height: 30px"/>
        <button class="btn btn-primary" type="button" style="margin-left: 20px;" onclick="searchWholesaler()">查询
        </button>
        <input class="btn btn-primary" type="button" value="新增供货商"
               style="margin-left:20px;" onclick="insertWholesaler()"/>
        <!--
        <input class="btn btn-primary" type="button" value="导入供货商"
               style="margin-left: 40px;position: absolute;right: 0px" onclick="addWholesaler()"/>-->
    </div>

    <?php
    //供货商列表
    if (isset($wholesalers)) {
        $len = count($wholesalers);
        $text = '<table class="table table-bordered">';
        $text .= '<tr class="info">';
        $text .= '<th>' . '供货商ID' . '</th>';
        $text .= '<th>' . '供货商' . '</th>';
        $text .= '<th>' . '省' . '</th>';
        $text .= '<th>' . '城市' . '</th>';
        $text .= '<th>' . '区域' . '</th>';
        $text .= '<th>' . '联系电话' . '</th>';
        $text .= '<th>' . '状态' . '</th>';
        $text .= '<th>' . '操作' . '</th>';
        $text .= '</tr>';
        foreach ($wholesalers as $wholesaler) {
            $text .= '<tr>';
            $text .= '<td>' . $wholesaler['id'] . '</td>';
            $text .= '<td>' . $wholesaler['name'] . '</td>';
            $text .= '<td>' . $wholesaler['province_name'] . '</td>';
            $text .= '<td>' . $wholesaler['city_name'] . '</td>';
            $text .= '<td>' . $wholesaler['district_name'] . '</td>';
            $text .= '<td>' . $wholesaler['phone'] . '</td>';
            $text .= '<td>' . $wholesaler['status_label'] . '</td>';
            $url = Url::toRoute(['wholesaler/detail', 'id' => $wholesaler['id']]);
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
     * 搜索供货商
     */
    function searchWholesaler() {
        var word = $("#keyword").val();
        var province = $("#province").val();
        var city = $("#city").val();
        var district = $("#district").val();
        window.location = getUrl('/wholesaler/list') + '?p=' + province + '&c=' + city + '&d=' + district + '&page=1' + '&w=' + word;
    }

    /**
     * 导入供货商
     */
    function addWholesaler() {
        var url = "<?php echo \yii\helpers\Url::toRoute(['import/merchant']) ?>";
        window.location = url;
    }

    /**
     * 新增供货商
     */
    function insertWholesaler() {
        var url = "<?php echo \yii\helpers\Url::toRoute(['wholesaler/add']) ?>";
        window.location = url;
    }

    function getUrl(path) {
        return path;
    }

    $(function () {
        //监听省切换
        $("#province").on('change', function () {
            var word = $("#keyword").val();
            window.location = getUrl('/wholesaler/list') + '?p=' + $(this).val() + '&w=' + word;
        });
        //监听市切换
        $("#city").on('change', function () {
            var province = getUrlParam('p');
            var word = $("#keyword").val();
            window.location = getUrl('/wholesaler/list') + '?p=' + province + '&c=' + $(this).val() + '&w=' + word;
        });
        //监听区域切换
        $("#district").on('change', function () {
            var province = getUrlParam('p');
            var city = getUrlParam('c');
            var word = $("#keyword").val();
            window.location = getUrl('/wholesaler/list') + '?p=' + province + '&c=' + city + '&d=' + $(this).val() + '&page=1' + '&w=' + word;
        });
    })
</script>