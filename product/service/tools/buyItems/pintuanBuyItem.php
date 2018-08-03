<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/8/1
 * Time: 14:22
 */

namespace service\tools\buyItems;

use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\PintuanActivityStore;
use service\resources\Exception;


/**
 * Class pintuanBuyItem
 */
class pintuanBuyItem extends buyItemBase
{
    private $pinPrice;
    private $pintuanId;
    private $pintuanActivityId;
    /** @var  PintuanActivity */
    private $pintuanActivity;
    public function __construct($pintuanId,$pintuanActivityId,$specificationId,$storeId = null)
    {
        $this->pintuanId = $pintuanId;
        $this->pintuanActivityId = $pintuanActivityId;

        $this->initPintuanActivity($storeId);
        $this->setPinPrice($specificationId);

        parent::__construct($this->pintuanActivity->product_id,$specificationId);
    }

    private function initPintuanActivity($storeId){
        if($this->pintuanId){
            $pintuan = Pintuan::findOne(['id' => $this->pintuanId, 'del' => 1]);
            if (!$pintuan) {
                Exception::throwException(Exception::PINTUAN_NOT_FIND);
            }
            $this->pintuanActivityId = $pintuan->pintuan_activity_id;
        }

        $this->pintuanActivity = PintuanActivity::findOne(['id' => $this->pintuanActivityId, 'del' => 1]);
        if (!$this->pintuanActivity) {
            Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
        }

        //校验活动是否结束
        if (strtotime($this->pintuanActivity->start_time) > time()) {
            Exception::throwException(Exception::PINTUAN_NOT_START);
        }
        if (strtotime($this->pintuanActivity->end_time) < time()) {
            Exception::throwException(Exception::PINTUAN_END);
        }

        //校验指定自提点拼团活动是否支持当前自提点
        if ($storeId && $this->pintuanActivity->place_type == PintuanActivity::PLACE_TYPE_ASSIGN_STORES) {
            $storeModel = PintuanActivityStore::find()
                ->where([
                    'pintuan_activity_id' => $this->pintuanActivityId,
                    'store_id' => $storeId,
                    'del' => 1
                ])->one();

            if(!$storeModel){
                Exception::throwException(Exception::PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE);
            }
        }
    }

    /**
     * @param $specificationId
     */
    private function setPinPrice($specificationId){
        $pintuanActivitySpecification = PintuanActivitySpecification::findOne([
            'pintuan_activity_id' => $this->pintuanActivityId,
            'specification_id' => $specificationId,
            'del' => 1,
        ]);
        if (!$pintuanActivitySpecification) {
            Exception::throwException(Exception::SPECIFICATION_NOT_JOIN_PINTUAN);
        }
        $this->pinPrice = $pintuanActivitySpecification->pin_price;
    }

    public function getItem(){
        $item = parent::getItem();

        $item['pintuan_activity_id'] = $this->pintuanActivityId;
        $item['pintuan_price'] = $this->pinPrice;
        $item['deal_price'] = $this->pinPrice;
        if($this->pintuanId){
            $item['pintuan_id'] = $this->pintuanId;
        }

        return $item;
    }


}