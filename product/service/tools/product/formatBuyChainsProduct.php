<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/7/27
 * Time: 18:06
 */

namespace service\tools\product;

use common\models\BuyChains;
use common\models\BuyChainsSpecification;
use framework\components\ToolsAbstract;
use service\resources\Exception;

/**
 * Class formatBuyChainsProduct
 * @package service\tools\product
 */
class formatBuyChainsProduct extends formatProduct
{
    protected $buyChainsId;
    protected $buyChains;
    protected $buyChainsSKU;

    public function __construct($buyChainsId)
    {
        $this->buyChainsId = $buyChainsId;
        $buyChains = BuyChains::findOne(['id' => $buyChainsId]);
        if (!$buyChains) {
            Exception::throwException(Exception::BUY_CHAINS_NOT_FIND);
        }

        if ($buyChains->status == 2 || strtotime($buyChains->end_time) < time()) {
            Exception::throwException(Exception::BUY_CHAINS_END);
        }

        if (strtotime($buyChains->start_time) > time()) {
            Exception::throwException(Exception::BUY_CHAINS_NOT_START);
        }

        $this->buyChains = $buyChains;
        parent::__construct($buyChains->product_id);
    }

    private function getBuyChainsSKU()
    {
        if (is_null($this->buyChainsSKU)) {
            //接龙活动规格，目前只有一个
            $buychainsSKU = BuyChainsSpecification::find()->where(['buy_chains_id' => $this->buyChainsId])->asArray()->all();
            foreach ($buychainsSKU as $SKU) {
                $this->buyChainsSKU[$SKU['specification_id']] = $SKU;
            }
        }
    }

    /**
     * 接龙活动的信息在这里设置
     * @return $this
     */
    public function getBasic()
    {
        parent::getBasic();
        unset($this->_result['min_price']);
        $this->_result['buy_chains_id'] = $this->buyChainsId;
        $this->_result['end_time'] = $this->buyChains->end_time;

        return $this;
    }

    public function getUserAlreadyBuyNum($userId, $specificationId)
    {
        $redis = ToolsAbstract::getRedis();
        $key = "buy_chains_user_buy_num_" . $this->buyChainsId . "_" . $specificationId;
        $res = $redis->hGet($key, $userId);

        return $res ?: 0;
    }

    /**
     * 获取规格信息
     * @param null $userId
     * @return $this
     */
    public function getSpecification($userId = null)
    {
        if (empty($this->_rawData['specification'])) {
            $this->_result['specification'] = [];
            return $this;
        }

        $this->getBuyChainsSKU();

        $minActivityPrice = 0;
        $minPrice = 0;
        $speItems = [];

        foreach ($this->_rawData['specification'] as $spe) {
            if (!isset($this->buyChainsSKU[$spe['specification_id']])) {
                continue;
            }

            $skuItem = $this->buyChainsSKU[$spe['specification_id']];

            $newSpe = [
                'price' => !empty($spe['price']) ? $spe['price'] : 0,
                'specification_id' => $spe['specification_id'],
                'attribute' => $spe['attribute'],
                'qty' => $skuItem['qty'],
                'barcode' => $spe['barcode'],
                'activity_price' => $skuItem['activity_price'],
                'sold_number' => ($skuItem['fake_sold_base'] + $skuItem['sold_num']),
                'limit_buy_num' => $skuItem['limit_buy_num'],
            ];

            if ($skuItem['limit_buy_num'] && $userId) {
                $newSpe['already_buy_num'] = $this->getUserAlreadyBuyNum($userId, $spe['specification_id']);
            }

            if ($minActivityPrice == 0 || $minActivityPrice > $newSpe['activity_price']) {
                $minActivityPrice = $newSpe['activity_price'];
            }
            if ($minPrice == 0 || $minPrice > $newSpe['price']) {
                $minPrice = $newSpe['price'];
            }

            foreach ($spe['attribute'] as $speItem) {
                if (!isset($speItems[$speItem['key']])) {
                    $speItems[$speItem['key']][] = $speItem['value'];
                } elseif (!in_array($speItem['value'], $speItems[$speItem['key']])) {
                    $speItems[$speItem['key']][] = $speItem['value'];
                }
            }

            $this->_result['specification'][] = $newSpe;
        }

        $this->_result['min_price'] = $minPrice;
        $this->_result['min_activity_price'] = $minActivityPrice;

        foreach ($speItems as $k => $v) {
            $this->_result['specification_item'][] = [
                'key' => $k,
                'value' => $v
            ];
        }

        return $this;
    }

}