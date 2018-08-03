<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanActivitySpecification;
use common\models\PintuanUser;
use common\models\PintuanActivity;
use common\models\Product;
use message\product\PintuanDetailReq;
use message\product\PintuanDetailRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\formatProductModel;
use service\tools\product\formatProduct;
use service\tools\product\formatSKUProduct;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 拼团详情
 */
class PintuanDetail extends ResourceAbstract
{
    /** @var  PintuanDetailReq */
    protected $request;
    private $pintuanId;
    private $pintuanActivityId;
    private $userId;
    /** @var  PintuanActivity */
    private $pintuanActivity;
    //当前拼团是否满团
    private $pintuanFullFlag = false;
    //当前用户是否在已参与当前拼团
    private $userInPintuanFlag = false;
    //当前拼团是否结束
    private $pintuanStopFlag = false;
    //拼团活动是否结束
    private $pintuanActivityStopFlag = false;

    public function run($data)
    {
        $this->doInit($data);
        $this->pintuanId = $this->request->getPintuanId();
        $this->userId = $this->request->getUserId();

        Tools::log($this->request->toArray(), 'PintuanDetail.log');
        $pintuan = Pintuan::findOne(['id' => $this->pintuanId]);
        if (!$pintuan) {
            Exception::throwException(Exception::PINTUAN_NOT_FIND);
        }

        $this->result['pintuan_end_time'] = $pintuan->end_time;
        if(strtotime($pintuan->end_time) <= time()){
            $this->pintuanStopFlag = true;
        }
        $this->pintuanActivityId = $pintuan->pintuan_activity_id;

        $pintuanUser = $this->getPintuanUser();
        $this->result['pintuan'] = $pintuanUser;//这个字段命名很奇特，但是为了兼容原数据结构，不改
        //用户是否参与当前拼团
        foreach ($pintuanUser as $user){
            if($user['user_id'] == $this->userId){
                $this->userInPintuanFlag = true;
                break;
            }
        }

        $this->pintuanActivity = $this->getPintuanActivity();
        $this->result['activity'] = [
            'id' => $this->pintuanActivityId,
            'product_id' => $this->pintuanActivity->product_id,
            'end_time' => $this->pintuanActivity->end_time,
            'member_num' => $this->pintuanActivity->member_num,
        ];

        if(strtotime($this->pintuanActivity->end_time) <= time()){
            $this->pintuanActivityStopFlag = true;
        }

        if($pintuan->member_num >= $this->pintuanActivity->member_num ){
            $this->pintuanFullFlag = true;
            $this->result['pintuan_info'] = 0;
        }else{
            $this->result['pintuan_info'] = $this->pintuanActivity->member_num - $pintuan->member_num;
        }

        $pintuanSpecification = PintuanActivitySpecification::find()
            ->select(['specification_id', 'pin_price'])
            ->where([
                'pintuan_activity_id' => $this->pintuanActivityId,
                'del' => PintuanActivitySpecification::NOT_DELETED
            ])->asArray()->all();
        Tools::log($pintuanSpecification,'pintuan_detail.log');

        if(empty($pintuanSpecification)){
            //数据异常
            Exception::throwException(Exception::SERVICE_NOT_AVAILABLE);
        }

        $pinPriceMap = [];
        foreach ($pintuanSpecification as $item) {
            $pinPriceMap[$item['specification_id']] = round($item['pin_price'] / 100, 2);
        }

        //前端希望在单规格是，数据结构不变，所以这里分开处理
        if(count($pintuanSpecification) == 1){//单规格
            $pintuanSpecification = current($pintuanSpecification);
            $specificationId = $pintuanSpecification['specification_id'];
            $product = (new formatSKUProduct($this->pintuanActivity->product_id,$specificationId))
                ->getBasic()
                ->getImages()
                ->getDescription()
                ->getSpecificationDesc()
                ->getData();

            $this->result['activity']['specification_id'] = $specificationId;
            $this->result['activity']['pin_price'] = $pinPriceMap[$specificationId];
            $this->result['specification'] = $product['specification_desc'];
        }else{//多规格
            $product = (new formatProduct($this->pintuanActivity->product_id))
                ->getBasic()
                ->getImages()
                ->getDescription()
                ->getSpecification()
                ->getData();
            $minPinPrice = 0;
//            $minPrice = 0;//因为拼团的规格可能不包含所有的商品规格，所以原$product['min_price']不能用，重新比较计算
//            Tools::log($product['specification'],'pintuan_detail.log');
            foreach ($product['specification'] as $k => &$spe){
                if(!isset($pinPriceMap[$spe['specification_id']])){
                    unset($product['specification'][$k]);
                    continue;
                }

                $spe['price'] = round($spe['price'] / 100, 2);
                $spe['pintuan_price'] = $pinPriceMap[$spe['specification_id']];
                if($minPinPrice == 0 || $minPinPrice > $pinPriceMap[$spe['specification_id']]){
                    $minPinPrice = $pinPriceMap[$spe['specification_id']];
                }
            }
            unset($spe);

            $this->result['activity']['min_pin_price'] = $minPinPrice;
            $this->result['specifications'] = $product['specification'];
            $this->result['specification_item'] = $product['specification_item'];
        }

        $this->result['product_name'] = $product['name'];
        $this->result['product_picture'] = $product['images'];

        if ($this->userInPintuanFlag) {
            $this->result['pintuan_status'] = $this->pintuanFullFlag ? 1:2;
        } else {
            $this->result['pintuan_status'] = $this->pintuanFullFlag ? 3:4;
        }

        //时间状态，1-拼团和拼团活动都未结束，2-拼团已结束，3-拼团活动已结束
        if($this->pintuanActivityStopFlag){
            $this->result['time_status'] = 3;
        }elseif($this->pintuanStopFlag){
            $this->result['time_status'] = 2;
        }else{
            $this->result['time_status'] = 1;
        }

        if($this->result['time_status'] = 2 || $this->result['pintuan_status'] == 3){
            $this->setElsePintuan();
        }

        $this->response->setFrom($this->result);
//        Tools::log($this->result, 'PintuanDetail.log');
        return $this->response;
    }

    /**
     * 查询其他拼团
     */
    private function setElsePintuan(){
        //其他拼团
        $pintuanList = Pintuan::find()
            ->where(['pintuan_activity_id' => $this->pintuanActivityId, 'status' => 1])
            ->andWhere(['>','end_time',date("Y-m-d H:i:s")])
            ->andWhere(['!=','id',$this->pintuanId])
            ->orderBy('member_num asc')
            ->all();
//        Tools::log($pintuanList,'PintuanDetail.log');

        // 拼团列表
        $elsePintuan = [];
        /** @var Pintuan $item */
        foreach ($pintuanList as $key => $item) {
            // 还差X人拼成（成团人数-当前团已下单数量，如果大于0显示“还差X人拼成”，如果等于0显示“已成团，可继续拼”）
            if($item->member_num < $this->pintuanActivity->member_num){
                $single['pintuan_info'] = intval($this->pintuanActivity->member_num - $item->member_num);
            }elseif($this->pintuanActivity->continue_pintuan == 1){
                $single['pintuan_info'] = 0;
            }else{//如果是成团后不能继续拼，已成团不显示
                continue;
            }
            $single['id'] = $item->id;
            $single['create_user_id'] = $item->create_user_id;

            // 根据团长的id查出头像和名称
            //这里应该收集团长用户id，返回api，api层再调用user服务，拿到所以团长的用户头像昵称，时间关系，这里先不处理
            /** @var PintuanUser $creatUserInfo */
//            $creatUserInfo = PintuanUser::find()
//                ->select(['pintuan_id', 'nick_name', 'avatar_url'])
//                ->where(['pintuan_id' => $item->id, 'user_id' => $item->create_user_id])
//                ->one();
//            if ($creatUserInfo) {
//                $single['id'] = $creatUserInfo->pintuan_id;
//                $single['nick_name'] = $creatUserInfo->nick_name;
//                $single['avatar_url'] = $creatUserInfo->avatar_url;
//            }

            $elsePintuan[] = $single;
        }

        $this->result['else_pintuan'] = $elsePintuan;
    }

    /**
     * 查询拼团活动
     */
    private function getPintuanActivity(){
        /**@var PintuanActivity $act * */
        $act = PintuanActivity::find()->select(['id', 'product_id', 'member_num', 'end_time'])
            ->where(['id' => $this->pintuanActivityId, 'status' => 1, 'del' => 1])
            ->one();

        if (!$act) {
            Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
        }

        return $act;
    }

    /**
     * 获取当前拼团参与用户
     * @return array|\yii\db\ActiveRecord[]
     */
    private function getPintuanUser(){
        $pintuanUser = PintuanUser::find()
            ->select('user_id,nick_name,avatar_url')
            ->where(['pintuan_id' => $this->pintuanId])
            ->orderBy('id DESC')
            ->asArray()
            ->all();

        return $pintuanUser;
    }

    public static function request()
    {
        return new PintuanDetailReq();
    }

    public static function response()
    {
        return new PintuanDetailRes();
    }
}