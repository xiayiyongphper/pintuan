<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/8/1
 * Time: 14:22
 */

namespace service\tools\buyItems;

use common\models\BuyChains;
use common\models\BuyChainsSpecification;
use common\models\BuyChainsStore;
use framework\components\ToolsAbstract;
use service\resources\Exception;


/**
 * Class buyChainsBuyItem
 */
class buyChainsBuyItem extends buyItemBase
{
    private $buyChainsPrice;
    private $buyChainsId;
    /** @var  BuyChains */
    private $buyChains;
    private $buyChainsSku;
    private $userId;
    private $buyNumber;
    public function __construct($buyChainsId,$specificationId,$userId,$buyNumber,$storeId = null)
    {
        $this->buyChainsId = $buyChainsId;
        $this->userId = $userId;
        $this->buyNumber = $buyNumber;
        $this->initBuyChains($storeId);

        parent::__construct($this->buyChains->product_id,$specificationId);

        $this->setBuyChainsPrice();
        $this->checkBuyTimes();
    }

    private function getBuyChainsSku(){
        if($this->buyChainsSku){
            return $this->buyChainsSku;
        }

        $buyChainsSku = BuyChainsSpecification::findOne([
            'buy_chains_id' => $this->buyChainsId,
            'specification_id' => $this->specificationId,
            'del' => 1,
        ]);
        if (!$buyChainsSku) {
            Exception::throwException(Exception::SPECIFICATION_NOT_JOIN_BUY_CHAINS);
        }

        $this->buyChainsSku = $buyChainsSku;
        return $this->buyChainsSku;
    }

    private function checkBuyTimes(){
        $buyChainsSku = $this->getBuyChainsSku();
        if(!$buyChainsSku->limit_buy_num){
            return true;
        }

        $redis = ToolsAbstract::getRedis();
        $key = "buy_chains_user_buy_num_" . $this->buyChainsId . "_" . $this->specificationId;
        $res = $redis->hGet($key, $this->userId);

        if($res && ((int)$res + $this->buyNumber) > $buyChainsSku->limit_buy_num){
            Exception::throwException(Exception::BUY_CHAINS_OVER_LIMIT);
        }

        return true;
    }

    /**
     * 初始化接龙活动对象
     * @param $storeId
     */
    private function initBuyChains($storeId){
        $this->buyChains = BuyChains::findOne(['id' => $this->buyChainsId, 'del' => 1]);
        if (!$this->buyChains) {
            Exception::throwException(Exception::BUY_CHAINS_NOT_FIND);
        }

        //校验活动是否结束
        if (strtotime($this->buyChains->start_time) > time()) {
            Exception::throwException(Exception::PINTUAN_NOT_START);
        }
        if (strtotime($this->buyChains->end_time) < time()) {
            Exception::throwException(Exception::PINTUAN_END);
        }

        //校验指定自提点接龙活动是否支持当前自提点
        if ($storeId && $this->buyChains->place_type == BuyChains::PLACE_TYPE_ASSIGN_STORES) {
            $storeModel = BuyChainsStore::find()
                ->where([
                    'buy_chains_id' => $this->buyChainsId,
                    'store_id' => $storeId,
                    'del' => 1
                ])->one();

            if(!$storeModel){
                Exception::throwException(Exception::BUY_CHAINS_NOT_SUPPORT_CURRENT_STORE);
            }
        }
    }

    /**
     *设置接龙价格
     */
    private function setBuyChainsPrice(){
        $buyChainsSku = $this->getBuyChainsSku();
        $this->buyChainsPrice = $buyChainsSku->activity_price;
    }

    public function getItem(){
        $item = parent::getItem();

        $item['buy_chains_id'] = $this->buyChains->id;
        $item['deal_price'] = $this->buyChainsPrice;

        return $item;
    }


}