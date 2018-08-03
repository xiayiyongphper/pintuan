<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/8/1
 * Time: 14:22
 */

namespace service\tools\buyItems;

use service\resources\Exception;
use service\tools\product\formatSKUProduct;


/**
 * Class buyItemBase
 */
abstract class buyItemBase
{
    protected $productId;
    protected $specificationId;

    public function __construct($productId,$specificationId)
    {
        $this->productId = $productId;
        $this->specificationId = $specificationId;
    }

    public function getItem(){
        $item = (new formatSKUProduct($this->productId,$this->specificationId))
            ->getBasic()
            ->getTopImage('180x180')
            ->getImages()
            ->getSpecificationInfo()
            ->getSpecificationDesc()
            ->getDescription()
            ->getData();
//            Tools::log($item,'buy_items.log');

        if ($item['status'] != 1) {
            Exception::throwException(Exception::PRODUCT_OFFLINE);
        }

        $item['images'] = is_array($item['images']) ? implode(';', $item['images']) : $item['images'];
        $item['description'] = is_array($item['description']) ? implode(';', $item['description']) : $item['description'];

        return $item;
    }


}