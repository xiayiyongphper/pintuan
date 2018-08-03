<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/4/15
 * Time: 16:18
 */

namespace service\components\search;


use common\models\Products;
use framework\data\Pagination;
use service\components\Redis;
use service\components\Tools;
use service\message\customer\CustomerResponse;
use service\message\merchant\searchProductByBarcodeResponse;
use service\message\merchant\searchProductRequest;
use service\message\merchant\searchProductResponse;
use service\models\ProductHelper;
use service\resources\Exception;
use service\resources\MerchantResourceAbstract;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Search extends SearchAbstract implements SearchInterface
{
    /**
     * Search constructor.
     * @param CustomerResponse $customer
     * @param searchProductRequest $searchRequest
     */
    public function __construct($customer,$searchRequest)
    {
        $this->customer = $customer;
        $this->searchRequest = $searchRequest;
    }

    /**
     * Function: search
     * Author: Jason Y. Wang
     * 搜索功能
     * @return mixed
     */
    public function search()
    {
        // TODO: Implement search() method.
    }

    public function barcodeSearch()
    {
        $customer = $this->customer;
        $response = new searchProductByBarcodeResponse();
        //barcode
        $keyword = $this->searchRequest->getKeyword();
        $wholesaler_id = $this->searchRequest->getWholesalerId();

        if(!$keyword){
            Exception::searchKeyWordInvalid();
        }

        //该区域的商家id
        $wholesalerIds = MerchantResourceAbstract::getWholesalerIdsByAreaId($customer->getAreaId());
        $wholesalers_info = Redis::getWholesalers($wholesalerIds);

        $productModel = new Products($customer->getCity());
        $productList = [];
        if($wholesaler_id){
            $productList = $productModel->find()
                ->where(['like','barcode',$keyword])
                ->andWhere(['status' => Products::STATUS_ENABLED, 'state' => Products::STATE_APPROVED])
                ->andWhere(['wholesaler_id' => $wholesaler_id])
                ->asArray()->all();
        }

        if(count($productList) == 0){
            $productList = $productModel->find()->alias('p')
                ->leftJoin('lelai_slim_merchant.le_merchant_store as s','p.wholesaler_id = s.entity_id')
                ->where(['like','p.barcode',$keyword])
                ->andWhere(['p.status' => Products::STATUS_ENABLED, 'p.state' => Products::STATE_APPROVED])
                ->andWhere(['p.wholesaler_id' => $wholesalerIds])
                ->orderBy('s.sort desc')
                ->asArray()->all();
        }

        $search_result = [];
        foreach ($productList as $product){
            //超过15个店铺停止遍历
            if(count($search_result) >= 15){
                break;
            }
            if(!isset($search_result[$product['wholesaler_id']])){
                $wholesalerInfo = unserialize($wholesalers_info[$product['wholesaler_id']]);
                $wholesalerInfo = MerchantResourceAbstract::getStoreDetail($wholesalerInfo);
                $search_result[$product['wholesaler_id']]['store'] = $wholesalerInfo;
            }

            if(isset($search_result[$product['wholesaler_id']]['products'])
                && count($search_result[$product['wholesaler_id']]['products']) >= 2){
                continue;
            }else{
                $search_result[$product['wholesaler_id']]['products'][] = MerchantResourceAbstract::getProductBriefArray($product);
            }
        }

        $result = [
            'barcode_search_result' => array_values($search_result),
        ];

        $response->setFrom(Tools::pb_array_filter($result));

        return $response;
    }

    /**
     * Function: packagingResponse
     * Author: Jason Y. Wang
     *
     * @return searchProductResponse
     */
    public function packagingResponse()
    {
        $customer = $this->customer;
        //从底层获取的数据
        //Tools::wLog('+++++++++++');
//        Tools::wLog($this->productIds);
//        Tools::log('+++++++++++','search_product.log');
//        Tools::log($this->customer,'search_product.log');
//        Tools::log($this->productIds,'search_product.log');

        $products = (new ProductHelper())->initWithProductIds($this->productIds,$this->customer->getCity())
            ->getTags()->getData();
//        Tools::log(count($products),'search_product.log');
//        Tools::wLog(count($products));

        //找出商品所属供应商
        $wholesaler_ids = ArrayHelper::getColumn($products,'wholesaler_id');
        $wholesaler_list = MerchantResourceAbstract::getStoreDetailBrief($wholesaler_ids,$this->customer->getAreaId(),'sort desc');
        //Tools::log($products,'wangyang.log');
        foreach ($products as &$product) {
            $product['purchased_qty'] = Tools::getPurchasedQty($customer->getCustomerId(), $customer->getCity(), $product['product_id']);
        }

        $result = [
            'product_list' => $products,
            'words' => $this->words,
            'wholesaler_list' => $wholesaler_list,
        ];

        if($this->pagination){
            $result['pages'] =  [
                'total_count' => $this->pagination->getTotalCount(),
                'page'        => $this->pagination->getCurPage(),
                'last_page'   => $this->pagination->getLastPageNumber(),
                'page_size'   => $this->pagination->getPageSize(),
            ];
        }

        $response = new searchProductResponse();
        $response->setFrom(Tools::pb_array_filter($result));
        return $response;
    }

}