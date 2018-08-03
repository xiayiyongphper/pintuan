<?php
/**
 * Created by PhpStorm.
 * User: lqs
 * Date: 2018/5/31 0031
 * Time: 14:51
 */

use yii\widgets\LinkPager;

$this->title = 'My Yii Application';
?>
<style>
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 0;
        border-radius: 4px;
    }

    .storeinfo {
        cursor: pointer;
    }
</style>
<div class="user-index">

    <p>
        用户列表
    </p>


    <form action="<?php echo \yii\helpers\Url::toRoute(['user/index']) ?>">
        <table class="table" align="right">
            <select name="has_order" id="has_order" style="width: 100px;margin-right: 10px">
                <option value="0" <?php echo $has_order == 0 ? "selected" : ""; ?>>是否下单</option>
                <option value="1" <?php echo $has_order == 1 ? "selected" : ""; ?>>是</option>
                <option value="2" <?php echo $has_order == 2 ? "selected" : ""; ?>>否</option>
            </select>

            <input type="text" name="phone" placeholder="请输入客户账号/手机号码" value="<?php echo $phone ?>"
                   style="border:1px solid #369;width:200px;margin-right: 30px">
            <button class="btn btn-primary" type="submit" style="width:80px" href="">查询</button>
            </tr>
        </table>
    </form>
    <table class="table table-bordered">
        <thead>
        <tr class="info">
            <th width="10%"></th>
            <th><p class="text-center">客户账号</p></th>
            <th><p class="text-center">省份</p></th>
            <th><p class="text-center">城市</p></th>
            <th><p class="text-center">自提点</p></th>
            <th><p class="text-center">创建时间</p></th>
            <th><p class="text-center">是否下单</p></th>
            <th><p class="text-center">权限</p></th>
        </tr>
        </thead>
        <tobdy>
            <?php if ($res) { ?>
                <?php foreach ($res as $val) { ?>
                    <tr>
                        <td align="center" class="list-item"><label>
                                <input type="radio" class="cb1" name="cb1" value="<?php echo $val['id'] ?>">
                            </label></td>
                        <td><p class="text-center"><?php echo $val['nickName'] ?></p></td>
                        <td><p class="text-center"><?php echo $val['province_name'] ?></p></td>
                        <td><p class="text-center"><?php echo $val['city_name'] ?></p></td>
                        <td class="storeinfo" id="<?php echo $val['id'] ?>"><p class="text-center"><?php
                                $userStore = \backend\models\PintuanUserStore::findById($val['id']);
                                if ($userStore) {
                                    $store = \backend\models\Store::findById($userStore->store_id);
                                    if ($store) {
                                        echo $store['detail_address'] . $store['store_phone'];
                                    }
                                }

                                ?></p></td>
                        <td><p class="text-center"><?php echo $val['created_at'] ?></p></td>
                        <?php
                        $has_order_msg = $val['has_order'] == 1 ? '是' : '否';
                        ?>
                        <td><p class="text-center"><?php echo $has_order_msg; ?></p></td>
                        <?php
                        $own_store_msg = $val['own_store_id'] == 0 ? '用户' : '店主';
                        ?>
                        <td><p class="text-center"><?php echo $own_store_msg; ?></p></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tobdy>
    </table>
    <table class="table" align="right">
        <tr>
            <td style="border: none;">
                <button class="btn btn-primary storeown" type="button">设为店长</button>
            </td>
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

<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../web/layer-v3.1.1/layer.js"></script>
<script>
    var firstLayer = null;
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

    $(function () {
        $('body').delegate('#sstoreown', 'click', function () {
            var parValue = $(".cb2:checked").eq(0).val();
            var arr = parValue.split("_");
            var param = {id: arr[0], uid: arr[1]};
            var url = "<?php echo \yii\helpers\Url::toRoute(['user/savestore'])?>";
            request(param, url, save_store_own);
        });
    });
    function save_store_own(res) {
        if(res){
            if(res.code==0){
                layer.msg('设置店主成功');
                layer.close(firstLayer);
                location.reload();
            }else{
                layer.msg('设置店主失败');
            }
        }
    }

    $(function () {
        $('body').delegate('#cstoreown', 'click', function () {
            var parValue = $(".cb2:checked").eq(0).val();
            var arr = parValue.split("_");
            var param = {id: arr[0], uid: arr[1]};
            var url = "<?php echo \yii\helpers\Url::toRoute(['user/cancelstore'])?>";
            request(param, url, cancel_store_own);
        });
    });
    function cancel_store_own(res) {
        if(res){
            if(res.code==0){
                layer.msg('取消店主成功');
                layer.close(firstLayer);
                location.reload();
            }else{
                layer.msg('取消店主失败');
            }
        }
    }

    $(function () {
        $('.storeown').on('click', function () {
            var param = {id: $(".cb1:checked").eq(0).val()};
            var url = "<?php echo \yii\helpers\Url::toRoute(['user/storeown'])?>";
            request(param, url, fill_stores_own);
        });
    });

    function fill_stores_own(res) {
        if (res) {
            var len = res.length;
            html = '<table class="table table-bordered" >';
            html += '<th width="60px"></th>';
            html += '<th width="160px"><p class="text-center">省份</p></th><th width="160px"><p class="text-center">城市</p></th width="260px"><td><p class="text-center">地址</p></td></tr>';
            for (var i = 0; i < len; i++) {
                var store = res[i];
                html += '<td align="center" width="60px"><input type="radio" class="cb2" name="cb2" value="' + store['id'] + '_' + store['user_id'] + '"/></td>';
                html += '<td width="160px"><p class="text-center">' + store['province'] + '</p></td>';
                html += '<td width="160px"><p class="text-center">' + store['city'] + '</p></td>';
                html += '<td width="260px"><p class="text-center">' + store['address'] + '</p></td></tr>';
            }
            html += '</table>';
            html += '<table class="table" align="right">';
            html += '<tr><td colspan="1" style = "border: none;" align="right"><button class="btn" id="cstoreown" type="button" >取消店长</button></td>' +
                '<td colspan="1" style = "border: none;"><button class="btn btn-primary" id="sstoreown" type="button">设为店长</button></td></tr>';
            html += '</table>';
            firstLayer = layer.open({
                type: 1,
                title: false,
                skin: 'layui-layer-rim', //加上边框
                area: ['600px'], //宽高
                content: html
            });
        }
    }

    $(function () {
        $('.storeinfo').on('click', function () {
            var param = {id: $(this).attr('id')};
            var url = "<?php echo \yii\helpers\Url::toRoute(['user/stores'])?>";
            request(param, url, fill_stores);
        });
    });
    function fill_stores(res) {
        if (res) {
            var len = res.length;
            html = '<table class="table table-bordered" >';
            html += '<td width="160px"><p class="text-center">店铺名称</p></td><td width="160px"><p class="text-center">联系电话</p></td width="360px"><td><p class="text-center">地址</p></td></tr>'
            for (var i = 0; i < len; i++) {
                var store = res[i];
                html += '<tr><td width="160px"><p class="text-center">' + store.name + '</p></td>';
                html += '<td width="160px"><p class="text-center">' + store.store_phone + '</p></td>';
                html += '<td width="360px"><p class="text-center">' + store.detail_address + '</p></td></tr>';
            }
            html += '</table>';
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['820px'], //宽高
                content: html
            });
        }
    }
</script>