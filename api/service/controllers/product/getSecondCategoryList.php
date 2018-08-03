<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\product;
use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\product\GetSecondCategoryProxy;
use service\callService\store\GetWholesalerDistrictListProxy;

/**
 * Class getSecondCategoryList
 */
class getSecondCategoryList extends ApiAbstract
{
    public function run($params){
        $this->doInit($params);

//        $this->_result['categories'] = [];
        $params = [
            'store_id' => [$this->_request['store_id']]
        ];
        $wholesalersResult = (new GetWholesalerDistrictListProxy($params))->sendRequest();
        $wholesalersResult = $wholesalersResult->toArray();
//        Tool::log($wholesalersResult,'second_category.log');
        $wholesalerIds = [];

        if(empty($wholesalersResult['wholesalers'])){
            return $this->_result;
        }

        foreach ($wholesalersResult['wholesalers'] as $item){
            $wholesalerIds[] = $item['wholesaler_id'];
        }

        $result = (new GetSecondCategoryProxy(['wholesaler_ids' => $wholesalerIds]))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
                ['auth_token',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
                ['store_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}