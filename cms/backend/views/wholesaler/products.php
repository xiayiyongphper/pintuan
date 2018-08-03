<?php

use yii\widgets\LinkPager;
use yii\data\Pagination;
use yii\helpers\Url;

$id = $_GET['id'];
$this->title = '供货商商品列表';
?>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<div class="row relative" style="height:600px;position:relative;">
    <a href="<?php echo Url::toRoute(['wholesaler/detail', 'id' => $id]) ?>">基础信息</a>
    <?php echo '<a style="margin-left: 20px" href="' . Url::toRoute(['wholesaler/pin', 'id' => $id]) . '">乐小拼信息</a>' ?>
    <label style="margin-left: 20px">商品列表</label>
    <?php
    //供货商列表
    if (isset($wholesalers)) {
        $len = count($wholesalers);
        $text = '<table class="table table-bordered" style="margin-top: 20px">';
        $text .= '<tr class="info">';
        $text .= '<th>' . '供货商' . '</th>';
        $text .= '<th>' . '省' . '</th>';
        $text .= '<th>' . '城市' . '</th>';
        $text .= '<th>' . '区域' . '</th>';
        $text .= '<th>' . '联系电话' . '</th>';
        $text .= '<th>' . '类型' . '</th>';
        $text .= '<th>' . '操作' . '</th>';
        $text .= '</tr>';
        foreach ($wholesalers as $wholesaler) {
            $text .= '<tr>';
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