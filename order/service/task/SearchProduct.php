<?php
/**
 * Created by PhpStorm.
 * User: zgr0629
 * Date: 21/1/2016
 * Time: 5:58 PM
 */

namespace service\task;

use framework\data\Pagination;
use framework\message\Message;
use service\components\search\searchProductEs;
use service\components\Tools;
use service\message\customer\CustomerResponse;
use service\message\merchant\searchProductRequest;
use service\message\merchant\searchProductResponse;
use service\resources\MerchantResourceAbstract;


class SearchProduct extends TaskAbstract
{
    public function run($data)
    {
//        Tools::log($data,'onTask.log');
        return $data;
    }
}