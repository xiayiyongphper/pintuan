<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\PintuanUser;
use common\models\Product;
use framework\components\ToolsAbstract;
use message\product\PintuanProductDetailReq;
use message\product\PintuanProductDetailRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\formatProductModel;
use service\tools\product\formatProduct;
use service\tools\product\formatSKUProduct;
use service\tools\Tools;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 拼团活动商品详情
 */
class PintuanProductDetail extends ResourceAbstract
{
    /** @var  PintuanProductDetailReq */
    protected $request;
    /** @var  PintuanActivity */
    private $pintuanActivity;

    public function run($data)
    {
        $this->doInit($data);
        $pintuanActivityId = $this->request->getPintuanActivityId();

        // 查询出拼团活动详情
        /** @var PintuanActivity $pintuanAct */
        $pintuanAct = PintuanActivity::find()
            ->where(['id' => $pintuanActivityId, 'status' => 1, 'del' => 1])
            ->andWhere(['<=', 'start_time', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'end_time', date('Y-m-d H:i:s')])
            ->one();
        if (!$pintuanAct) {
            Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
        }
        $this->pintuanActivity = $pintuanAct;
        $result = [
            'activity' => $pintuanAct->toArray()
        ];

        $pintuanSpecification = PintuanActivitySpecification::find()
            ->select(['specification_id', 'pin_price'])
            ->where([
                'pintuan_activity_id' => $pintuanActivityId,
                'del' => PintuanActivitySpecification::NOT_DELETED
            ])->asArray()->all();

        if (empty($pintuanSpecification)) {
            //数据异常
            Exception::throwException(Exception::SERVICE_NOT_AVAILABLE);
        }

        $pinPriceMap = [];
        foreach ($pintuanSpecification as $item) {
            $pinPriceMap[$item['specification_id']] = round($item['pin_price'] / 100, 2);
        }
//        Tools::log($pinPriceMap,'pintuan_detail.log');

        $productModel = (new formatProductModel($pintuanAct->product_id))
            ->getBasic()
            ->getImages()
            ->getDescription();

        //前端希望在单规格是，数据结构不变，所以这里分开处理
        if (count($pintuanSpecification) == 1) {//单规格
            $pintuanSpecification = current($pintuanSpecification);
            $specificationId = $pintuanSpecification['specification_id'];
            $product = (new formatSKUProduct($this->pintuanActivity->product_id, $specificationId))
                ->getBasic()
                ->getImages()
                ->getDescription()
                ->getSpecificationDesc()
                ->getPrice()
                ->getData();

            $result['activity']['specification_id'] = $specificationId;
            $result['activity']['pin_price'] = $pinPriceMap[$specificationId];
            $result['specification'] = $product['specification_desc'];
            $result['product_price'] = round($product['price'] / 100, 2);
        } else {//多规格
//            $product = $productModel->getSpecification()->getData();
            $product = (new formatProduct($this->pintuanActivity->product_id))
                ->getBasic()
                ->getImages()
                ->getDescription()
                ->getSpecification()
                ->getData();

            $minPinPrice = 0;
            $minPrice = 0;//因为拼团的规格可能不包含所有的商品规格，所以原$product['min_price']不能用，重新比较计算
//            Tools::log($product['specification'],'pintuan_detail.log');
            foreach ($product['specification'] as $k => &$spe) {
                if (!isset($pinPriceMap[$spe['specification_id']])) {
                    unset($product['specification'][$k]);
                    continue;
                }

                $spe['price'] = round($spe['price'] / 100, 2);
                $spe['pintuan_price'] = $pinPriceMap[$spe['specification_id']];
                if ($minPinPrice == 0 || $minPinPrice > $pinPriceMap[$spe['specification_id']]) {
                    $minPinPrice = $pinPriceMap[$spe['specification_id']];
                }
                if ($minPrice == 0 || $minPrice > $spe['price']) {
                    $minPrice = $spe['price'];
                }
            }
            unset($spe);

            $result['activity']['min_pin_price'] = $minPinPrice;
            $result['specifications'] = $product['specification'];
            $result['specification_item'] = $product['specification_item'];
            $result['min_price'] = $minPrice;
        }

        $result['product_name'] = $product['name'];
        $result['product_picture'] = $product['images'];
        $result['product_detail'] = $product['description'];

        // 查询X团正在拼
        $pintuanAll = Pintuan::find()
            ->where([
                'pintuan_activity_id' => $pintuanAct->id,
                'status' => 1,
                'del' => 1
            ])->andWhere(['>', 'end_time', date('Y-m-d H:i:s')]);
        ToolsAbstract::log($pintuanAll->createCommand()->rawSql,'pintuan_detail.log');

        $has_pintuan = $pintuanAll->count();
        $pintuanListInfo = $pintuanAll->orderBy('member_num asc')->all();
        // 拼团列表
        $pintuanList = [];
        /** @var Pintuan $item */
        $complete_member_num = 0;
        foreach ($pintuanListInfo as $key => $item) {
            // 还差X人拼成（成团人数-当前团已下单数量，如果大于0显示“还差X人拼成”，如果等于0显示“已成团，可继续拼”）
            if ($item->member_num < $pintuanAct->member_num) {
                $pintuan['pintuan_info'] = intval($pintuanAct->member_num - $item->member_num);
            } elseif ($pintuanAct->continue_pintuan == 1) {
                $pintuan['pintuan_info'] = 0;
            } else {//如果是成团后不能继续拼，已成团不显示
                continue;
            }

            // 根据团长的id查出头像和名称
            /** @var PintuanUser $creatUserInfo */
            $creatUserInfo = PintuanUser::find()->select(['nick_name', 'avatar_url'])
                ->where(['pintuan_id' => $item->id, 'user_id' => $item->create_user_id])
                ->one();
            if ($creatUserInfo) {
                $pintuan['nick_name'] = $creatUserInfo->nick_name;
                $pintuan['avatar_url'] = $creatUserInfo->avatar_url;
            }
            $pintuan['id'] = $item->id;
            $pintuan['end_time'] = $item->end_time;
            $pintuanList[] = $pintuan;

//            if ($item->member_num == $pintuanAct->member_num && $pintuanAct->continue_pintuan == 1) {
//                $pintuanList[$key]['pintuan_info'] = 0;
//            } elseif ($item->member_num < $pintuanAct->member_num) {
//                $pintuanList[$key]['pintuan_info'] = intval($pintuanAct->member_num - $item->member_num);
//            }
            $complete_member_num += $item->member_num;
        }

        $result['activity']['has_pintuan'] = $has_pintuan;// X团正在拼
        $result['activity']['complete_member_num'] = $complete_member_num;// 拼单人数
        $result['pintuan'] = $pintuanList;

        //ToolsAbstract::log($result,'pintuan_detail.log');
        $this->response->setFrom($result);
        return $this->response;
    }

    public static function request()
    {
        return new PintuanProductDetailReq();
    }

    public static function response()
    {
        return new PintuanProductDetailRes();
    }
}