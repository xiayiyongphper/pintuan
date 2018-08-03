<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/4/15
 * Time: 16:12
 */

namespace service\components\search;


use framework\data\Pagination;
use service\message\customer\CustomerResponse;
use service\message\merchant\searchProductRequest;

abstract class SearchAbstract
{
    /** @var CustomerResponse $customer */
    protected $customer;

    protected $words;
    protected $productIds;
    protected $keyword;
    /** @var  Pagination $pagination */
    protected $pagination;

    /** @var  searchProductRequest  $searchRequest */
    protected $searchRequest;

    public abstract function packagingResponse();

}