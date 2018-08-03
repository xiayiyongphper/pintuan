<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/8/1
 * Time: 14:22
 */

namespace service\tools\buyItems;

use common\models\NewActProduct;

/**
 * Class buyItemsBase
 */
class normalBuyItem extends buyItemBase
{
    private $newUserPrice;

    public function __construct($productId, $specificationId, $newActId = null)
    {
        //新人活动，新人活动原来是在api层先调用服务拿到新人活动，然后传过来活动id，获取新人活动id的时候已经做过配送范围校验；但是应该放在这里更合理，暂不优化
        if ($newActId) {
            $actProduct = NewActProduct::findOne([
                'act_id' => $newActId,
                'spec_id' => $specificationId,
                'del' => NewActProduct::NOT_DELETED,
            ]);

            if ($actProduct) {
                $this->newUserPrice = $actProduct->price;
            }
        }

        parent::__construct($productId, $specificationId);
    }

    public function getItem()
    {
        $item = parent::getItem();

        $item['deal_price'] = $item['price'];
        if ($this->newUserPrice) {
            $item['new_user_price'] = $item['deal_price'] = $this->newUserPrice;
        }

        return $item;
    }


}