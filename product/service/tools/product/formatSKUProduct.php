<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 10:42
 */

namespace service\tools\product;

use common\models\Product;
use common\models\Specification;
use framework\components\ToolsAbstract;
use service\resources\Exception;
use service\tools\Tools;

/**
 * 指定规格商品
 * Class ProductModel
 * @package service\tools
 */
class formatSKUProduct extends formatProduct
{
    const PRODUCT_KEY = 'pintuan_products';

    /** @var Product */
    protected $_rawData;
    protected $_result = [];
    protected $_id;
    protected $specificationId;

    /**
     * ProductModel constructor.
     * @param int $product
     */
    public function __construct($productId,$specificationId)
    {
        parent::__construct($productId);
        $this->specificationId = $specificationId;

        //校验规格id是否存在
        $specificationExistFlat = false;
        foreach ($this->_rawData['specification'] as $specification) {
            if ($specification['specification_id'] == $this->specificationId) {
                $specificationExistFlat = true;
                break;
            }
        }
        if (!$specificationExistFlat) {
            Exception::throwException(Exception::SPECIFICATION_NOT_FIND);
        }
    }

    /**
     * 基础信息
     * @return $this
     */
    public function getBasic()
    {
        parent::getBasic();
        $this->_result['specification_id'] = $this->specificationId;
        return $this;
    }

    /**
     * 规格描述（选定一个规格id时，可以有）
     * 不用考虑规格id不存在的情况，因为构造函数中已经保证了
     * @param $specificationId
     * @return $this
     */
    public function getSpecificationDesc()
    {
        $attrParts = [];
        foreach ($this->_rawData['specification'] as $specification) {
            if ($specification['specification_id'] == $this->specificationId) {
                foreach ($specification['attribute'] as $attr) {
                    $attrParts[] = $attr['value'];
                }
                break;
            }
        }
        $this->_result['specification_desc'] = implode('、', $attrParts);

        return $this;
    }

    /**
     * 不用考虑规格id不存在的情况，因为构造函数中已经保证了
     * @param $specificationId
     * @param array $actProduct
     * @return $this
     */
    public function getPrice()
    {
        foreach ($this->_rawData['specification'] as $specification) {
            if ($specification['specification_id'] == $this->specificationId) {
                $this->_result['price'] = $specification['price'];

                break;
            }
        }

        return $this;
    }

    /**
     * 不用考虑规格id不存在的情况，因为构造函数中已经保证了
     * @param $specificationId
     * @return $this
     */
    public function getSpecificationInfo()
    {
        foreach ($this->_rawData['specification'] as $specification) {
            if ($specification['specification_id'] == $this->specificationId) {
                $this->_result['specification_id'] = $specification['specification_id'];
                $this->_result['item_detail'] = $specification['item_detail'];
                $this->_result['purchase_price'] = $specification['purchase_price'];
                $this->_result['pick_commission'] = $specification['pick_commission'];
                $this->_result['promote_commission'] = $specification['promote_commission'];
                $this->_result['price'] = $specification['price'];

                break;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_result;
    }

}