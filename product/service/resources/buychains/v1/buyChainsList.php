<?php

namespace service\resources\buychains\v1;

use common\models\BuyChains;
use common\models\BuyChainsStore;
use common\models\BuyChainsUser;
use common\models\Product;
use message\product\BuyChainsListReq;
use message\product\BuyChainsListRes;
use service\resources\ResourceAbstract;
use service\tools\product\formatBuyChainsProduct;
use service\tools\Tools;

/**
 * Class buyChainsList
 */
class buyChainsList extends ResourceAbstract
{
    /** @var BuyChainsListReq */
    protected $request;

    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param string $bytes
     * @return mixed
     */
    public function run($data)
    {
        $this->doInit($data);

        $bcIds1 = BuyChainsStore::find()->select('buy_chains_id')
            ->where(['store_id' => $this->request->getStoreId(), 'del' => 1])->column();
        $bcIds2 = BuyChains::find()
            ->where(['wholesaler_id' => $this->request->getWholesalerId(), 'status' => Product::STATUS_ONLINE, 'del' => Product::NOT_DELETED])
            ->column();
        $bcIds = array_unique(array_merge($bcIds1, $bcIds2));
        $bcIdArr = BuyChains::find()
            ->where(['id' => $bcIds, 'status' => Product::STATUS_ONLINE, 'del' => Product::NOT_DELETED])
            ->andWhere(['<=', 'start_time', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'end_time', date('Y-m-d H:i:s')])
            ->limit(15)
            ->column();

        $userId = $this->request->getUserId();
        $result = [];
        foreach ($bcIdArr as $id) {
            try{
                $buyChains = (new formatBuyChainsProduct($id))->getBasic()->getTopImage('180x180')->getSpecification($userId)->getData();
                $buyChains['user_id'] = BuyChainsUser::find()->select('user_id')
                    ->where(['buy_chains_id' => $id, 'store_id' => $this->request->getStoreId()])
                    ->orderBy('id desc')
                    ->limit(3)
                    ->column();

                $result[] =$buyChains;
            }catch(\Exception $e){
                continue;
            }
        }

        $this->response->setFrom(['buy_chains' => $result]);
        return $this->response;
    }

    public static function request()
    {
        return new BuyChainsListReq();
    }

    public static function response()
    {
        return new BuyChainsListRes();
    }
}