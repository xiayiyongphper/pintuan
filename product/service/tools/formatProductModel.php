<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 10:42
 */

namespace service\tools;

use common\models\Product;
use common\models\Specification;
use framework\components\ToolsAbstract;
use service\resources\Exception;

/**
 * Class ProductModel
 * @package service\tools
 */
class formatProductModel
{
    const PRODUCT_KEY = 'pintuan_products';

    /** @var Product */
    protected $_rawData;
    protected $_result = [];
    protected $_id;

    /**
     * ProductModel constructor.
     * @param int $product
     */
    public function __construct($productId)
    {
        $productId = intval($productId);
        if ($productId < 1) {
            Exception::throwException(Exception::INVALID_PARAM);
        }

        $this->_id = $productId;
        $this->initRawData();
    }

    /**
     * @return bool
     */
    protected function initRawData()
    {
        $redis = Tools::getRedis();
        $product = $redis->hget(self::PRODUCT_KEY, $this->_id);
        if ($product) {
            $this->_rawData = unserialize($product);
            return true;
        }

        $product = $this->getFormatData();
        $redis->hSet(self::PRODUCT_KEY, $this->_id, serialize($product));
        $this->_rawData = $product;
        return true;
    }

    /**
     * @return array
     */
    protected function getFormatData()
    {
        $product = Product::findOne([
            'id' => $this->_id,
            'del' => Product::NOT_DELETED,
        ]);

        if (!$product) {
            Exception::throwException(Exception::PRODUCT_NOT_FIND);
        }

        $formatProduct = $product->toArray();
        $formatProduct['product_id'] = $formatProduct['id'];
        unset($formatProduct['id']);
        //商品图片解析为数组
        $formatProduct['images'] = [];
        if ($product->images) {
            $formatProduct['images'] = explode(';', $product->images);
        }
        //商品描述图片解析为数组，目前商品描述全部为图片
        $formatProduct['description'] = [];
        if ($product->images) {
            $formatProduct['description'] = explode(';', $product->description);
        }

        //格式化规格 获取最低价格
        list($formatProduct['specification'], $formatProduct['specification_item'], $formatProduct['min_price']) = $this->formatSpecification($product);

        return $formatProduct;
    }


    protected function formatSpecification(Product $product)
    {
        $formatSpecification = [];
        $specificationItem = [];
        $minPrice = 0;
        $specification = $product->specifications;
//        Tools::log($specification,'pro.log');
        if (!$specification) {
            return [$formatSpecification, $specificationItem, $minPrice];
        }

        $speItems = [];
        /** @var Specification $item */
        foreach ($specification as $item) {
            if ($item->del != 1) {
                continue;
            }

            $spe = $item->toArray();
//            $spe['price'] = $spe['price'] ? round($spe['price'] / 100,2) : 0;
            $spe['specification_id'] = $spe['id'];
            unset($spe['id']);
            //属性
            $spe['attribute'] = [];
            $attr = json_decode($spe['item_detail'], true);
            foreach ($attr as $k => $v) {
                $spe['attribute'][] = [
                    'key' => $k,
                    'value' => $v
                ];

                if (!isset($speItems[$k])) {
                    $speItems[$k][] = $v;
                } else {
                    if (!in_array($v, $speItems[$k])) {
                        $speItems[$k][] = $v;
                    }
                }
            }

            $formatSpecification[] = $spe;
            if (empty($minPrice) || $minPrice > $spe['price']) {
                $minPrice = $spe['price'];
            }
        }

        foreach ($speItems as $k => $v) {
            $specificationItem[] = [
                'key' => $k,
                'value' => $v
            ];
        }

        return [$formatSpecification, $specificationItem, $minPrice];
    }

    /**
     * 基础信息
     * @return $this
     */
    public function getBasic()
    {
        $this->_result['product_id'] = $this->_rawData['product_id'];
        $this->_result['name'] = !empty($this->_rawData['name']) ? $this->_rawData['name'] : '';
        $this->_result['sub_name'] = !empty($this->_rawData['sub_name']) ? $this->_rawData['sub_name'] : '';
        $this->_result['min_price'] = !empty($this->_rawData['min_price']) ? round($this->_rawData['min_price'] / 100, 2) : 0;
        $this->_result['status'] = $this->_rawData['status'];
        $this->_result['wholesaler_id'] = $this->_rawData['wholesaler_id'] ?: 0;
        $this->_result['third_category_id'] = $this->_rawData['third_category_id'];
        $this->_result['sold_num'] = $this->_rawData['sold_num'] + $this->_rawData['fake_sold_base'];
        return $this;
    }

    /**
     * 首张图片
     * @return $this
     */
    public function getTopImage($size = null)
    {
        $this->_result['image'] = !empty($this->_rawData['images']) ? current($this->_rawData['images']) : '';
        if ($size) {
            $this->_result['image'] = Tools::getImage($this->_result['image'], $size);
        }

        return $this;
    }

    /**
     * 商品图片
     * @return $this
     */
    public function getImages()
    {
        $this->_result['images'] = !empty($this->_rawData['images']) ? $this->_rawData['images'] : [];
        return $this;
    }

    /**
     * 商品描述图片
     * @return $this
     */
    public function getDescription()
    {
        $this->_result['description'] = !empty($this->_rawData['description']) ? $this->_rawData['description'] : [];
        return $this;
    }

    /**
     * 规格信息
     * @return $this
     */
    public function getSpecification($actProduct = [])
    {
        if (empty($this->_rawData['specification'])) {
            $this->_result['specification'] = [];
            return $this;
        }

        foreach ($this->_rawData['specification'] as $spe) {
            $newSpe = [
                'price' => !empty($spe['price']) ? round($spe['price'] / 100, 2) : 0,
                'specification_id' => $spe['specification_id'],
                'attribute' => $spe['attribute'],
                'qty' => $spe['qty'],
                'barcode' => $spe['barcode'],
                'image' => $spe['image'],
            ];

            //新人活动
            if (isset($actProduct[$spe['specification_id']])) {
                $newSpe['new_price'] = round($actProduct[$spe['specification_id']]['price'] / 100, 2);
            }

            $this->_result['specification'][] = $newSpe;
        }

        $this->_result['specification_item'] = !empty($this->_rawData['specification_item']) ? $this->_rawData['specification_item'] : [];
        return $this;
    }

    /**
     * 规格描述（选定一个规格id时，可以有）
     * @param $specificationId
     * @return $this
     */
    public function getSpecificationDesc($specificationId)
    {
        $specificationExistFlat = false;
        $this->_result['specification_desc'] = '';
        if (!empty($this->_rawData['specification'])) {
            $attrParts = [];
            foreach ($this->_rawData['specification'] as $specification) {
                if ($specification['specification_id'] == $specificationId) {
                    foreach ($specification['attribute'] as $attr) {
                        $attrParts[] = $attr['value'];
                    }
                    $specificationExistFlat = true;
                    break;
                }
            }
            $this->_result['specification_desc'] = implode('、', $attrParts);
        }

        if (!$specificationExistFlat) {
            Exception::throwException(Exception::SPECIFICATION_NOT_FIND);
        }

        return $this;
    }

    /**
     * @param $specificationId
     * @return $this
     */
    public function getPrice($specificationId)
    {
        $specificationExistFlat = false;
        $this->_result['price'] = '';
        if (!empty($this->_rawData['specification'])) {
            foreach ($this->_rawData['specification'] as $specification) {
                if ($specification['specification_id'] == $specificationId) {
                    $this->_result['price'] = round($specification['price'] / 100, 2);
                    $specificationExistFlat = true;
                    break;
                }
            }
        }

        if (!$specificationExistFlat) {
            Exception::throwException(Exception::SPECIFICATION_NOT_FIND);
        }

        return $this;
    }

    public function getSpecificationInfo($specificationId)
    {
        $specificationExistFlat = false;
        $this->_result['specification_id'] = 0;
        $this->_result['item_detail'] = '';
        $this->_result['purchase_price'] = 0;
        $this->_result['pick_commission'] = 0;
        $this->_result['promote_commission'] = 0;
        $this->_result['price'] = 0;
        if (!empty($this->_rawData['specification'])) {
            foreach ($this->_rawData['specification'] as $specification) {
                //Tools::log($specification['specification_id']."===========".$specificationId,'format_pro.log');
                if ($specification['specification_id'] == $specificationId) {
                    $this->_result['specification_id'] = $specification['specification_id'];
                    $this->_result['item_detail'] = $specification['item_detail'];
                    $this->_result['purchase_price'] = $specification['purchase_price'];
                    $this->_result['pick_commission'] = $specification['pick_commission'];
                    $this->_result['promote_commission'] = $specification['promote_commission'];
                    $this->_result['price'] = $specification['price'];
                    $this->_result['deal_price'] = $specification['price'];
                    $specificationExistFlat = true;
                    break;
                }
            }
        }

        if (!$specificationExistFlat) {
            Exception::throwException(Exception::SPECIFICATION_NOT_FIND);
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