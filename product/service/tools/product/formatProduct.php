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
use service\resources\Exception;
use service\tools\Tools;

/**
 * 输出金额以分为单位
 * Class ProductModel
 * @package service\tools
 */
class formatProduct
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
        $this->_result['sub_name'] = !empty($this->_rawData['sub_name']) ? $this->_rawData['sub_name'] : '';
        $this->_result['name'] = !empty($this->_rawData['name']) ? $this->_rawData['name'] : '';
        $this->_result['min_price'] = !empty($this->_rawData['min_price']) ? $this->_rawData['min_price'] : 0;
        $this->_result['status'] = $this->_rawData['status'];
        $this->_result['wholesaler_id'] = $this->_rawData['wholesaler_id'];
        $this->_result['third_category_id'] = $this->_rawData['third_category_id'];
        $this->_result['sold_num'] = $this->_rawData['sold_num'] + $this->_rawData['fake_sold_base'];
        return $this;
    }

    /**
     * 首张图片
     * @param null $size
     * small = 180x180
     * medium = 232x232
     * big = 388x388
     * large = 640x300
     * xlarge = 560x560
     * huge = 600x600
     * xhuge = 1200x1200
     * original = *
     *
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
    public function getSpecification()
    {
        if (empty($this->_rawData['specification'])) {
            $this->_result['specification'] = [];
            return $this;
        }

        foreach ($this->_rawData['specification'] as $spe) {
            $newSpe = [
                'price' => !empty($spe['price']) ? $spe['price'] : 0,
                'specification_id' => $spe['specification_id'],
                'attribute' => $spe['attribute'],
                'qty' => $spe['qty'],
                'barcode' => $spe['barcode'],
            ];

            $this->_result['specification'][] = $newSpe;
        }

        $this->_result['specification_item'] = !empty($this->_rawData['specification_item']) ? $this->_rawData['specification_item'] : [];
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