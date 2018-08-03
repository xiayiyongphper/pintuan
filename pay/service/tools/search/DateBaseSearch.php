<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/4/15
 * Time: 16:41
 */

namespace service\components\search;


use common\models\Products;
use service\components\Tools;
use framework\data\Pagination;
use service\message\merchant\searchProductRequest;
use service\resources\MerchantResourceAbstract;
use yii\helpers\ArrayHelper;

class DateBaseSearch extends Search
{
    public function search()
    {
        /** @var  searchProductRequest  $searchRequest */
        $request = $this->searchRequest;
        $customer = $this->customer;
        // 组装查询条件
        $condition = [];
        // 商家id
        if ($request->getWholesalerId()) {
            // 查指定的商家
            $condition['wholesaler_id'] = $request->getWholesalerId();
        } else {
            // 否则就查该区域的商家id
            $condition['wholesaler_id'] = MerchantResourceAbstract::getWholesalerIdsByAreaId($customer->getAreaId());
        }

        // keyword
        $keyword = $request->getKeyword();
        if ($keyword) {
            $words = array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($keyword))));
            foreach ($words as $word) {
                $condition = ['and', $condition,
                    ['like', 'CONCAT(brand, name, specification, package_spe, package_num, package)', $word],
                ];
            }
        }

		//分类过滤
		//通过分类过滤商品
		$categoryId = $this->searchRequest->getCategoryId();
		$categoryLevel = $this->searchRequest->getCategoryLevel() ?: Tools::getCategoryLevelByID($categoryId);
		if ($categoryId) {
			switch ($categoryLevel) {
				case 1:
					$category = 'first_category_id';
					break;
				case 2:
					$category = 'second_category_id';
					break;
				case 3:
					$category = 'third_category_id';
					break;
				default :
					$category = 'third_category_id';
					break;
			}
			$condition = ['and', $condition,
				[$category=>$categoryId],
			];
		}

        // 品牌
        $brand = $request->getBrand();
        if ($brand) {
            $condition = ['and', $condition,
                ['like', 'brand', $brand],
            ];
        }


        // 商品的必要条件
		$condition = ['and', $condition,
			['not', ['brand' => null]]// 品牌不为空
		];
		$condition = ['and', $condition,
			['not', ['brand' => '']]// 品牌不为空
		];

		//分页设置
		$page = $this->searchRequest->getPage() ?: 1;
		$pageSize = $this->searchRequest->getPageSize() ?: 20;

		$productModel = new Products($this->customer->getCity());
		$productsAll = $productModel->find()
			->select('entity_id')
			->where($condition)
			->andWhere(['state'=>2])//通过审核
			->andWhere(['status'=>1])//上架
		;
		$count = $productsAll->count();

		$pages = new Pagination(['totalCount' => $count]);
		$pages->setCurPage($page);
		$pages->setPageSize($pageSize);

		$products = $productsAll
			->offset($pages->getOffset())
			->limit($pages->getLimit())
		;
		$sql = $products->createCommand()->getRawSql();
		$products = $products
			->asArray()
			->all();
		;
		$this->pagination = $pages;

		if($customer->getCustomerId()==1091){
			Tools::log($sql, 'searchTest.log');
		}
		$this->productIds = ArrayHelper::getColumn($products,'entity_id');
		return $this->packagingResponse();
    }
}