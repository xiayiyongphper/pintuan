<?php

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\product\GetTopicInfoProxy;
use service\callService\product\PintuanActivityDetailProxy;
use service\callService\product\PintuanActivityListProxy;
use service\callService\product\GetProductDetailProxy;
use service\callService\product\ProductListProxy;
use service\callService\store\HomeProxy;
use service\callService\store\MarketConfigureProxy;

class home extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $storeRequest = [
            'store_id' => $this->_request['store_id'],
        ];
        $home = (new HomeProxy('store', 'store.home', $storeRequest))->sendRequest()->toArray();

        $activity = [];
        if ($this->_wholesalerIds) {
            $activityRequest = [
                'store_id'      => $this->_request['store_id'],
                'wholesaler_id' => $this->_wholesalerIds
            ];
            $activity = (new PintuanActivityListProxy('product', 'pintuan.PintuanActivityList', $activityRequest))->sendRequest()->toArray();

            isset($home['banners']) && $home['banners'] = $this->checkBanners($home['banners']);
            isset($home['topics']) && $home['topics'] = $this->checkTopics($home['topics']);
        } else {
            unset($home['banners']);
            $home['banners']['img_url'] = 'http://assets.lelai.com/images/files/merchant/20180625/source/b54f889ccb72de45404d6f1a74a189c6_bb3123dbf67b74ea5a29cba1da174080.jpeg';
        }

        //招募团长banner、招募团长详情图片
        $marketConfigureInfo = (new MarketConfigureProxy(['type'=>3]))->sendRequest()->toArray();
        $home['marketConfigure'] = $marketConfigureInfo;

        return array_merge($home, $activity);
    }

    public function checkBanners($banners)
    {
        if (empty($banners))
            return $banners;

        $data = [];
        foreach ($banners as $banner) {
            $value = intval($banner['value']);
            if ($banner['type'] == 1) {//商品
                $product = (new GetProductDetailProxy(['product_id' => $value]))->sendRequest()->toArray();
                if ($product && in_array($product['wholesaler_id'], $this->_wholesalerIds)) {
                    $data[] = $banner;
                }
            } elseif ($banner['type'] == 2) {//拼团活动
                $request = [
                    'activity_id'   => [$value],
                    'store_id'      => $this->_request['store_id'],
                    'wholesaler_id' => $this->_wholesalerIds
                ];
                $activity = (new PintuanActivityDetailProxy('product', 'pintuan.PintuanActivityDetail', $request))
                    ->sendRequest()->toArray();
                if (isset($activity['activity']) && $activity['activity']) {
                    $data[] = $banner;
                }
            } elseif ($banner['type'] == 3) {//专题
                $result = (new GetTopicInfoProxy(['id' => $value]))->sendRequest()->toArray();
                if ($this->checkTopics([$result])) {
                    $data[] = $banner;
                }
            }

            if (count($data) >= 6) {
                break;
            }
        }
        return $data;
    }

    public function checkTopics($topics)
    {
        if (empty($topics))
            return $topics;

        $data = [];
        foreach ($topics as $topic) {
            if ($topic['type'] == 2) {//拼团
                $request = [
                    'activity_id'   => array_map('intval', explode(',', $topic['products'])),
                    'store_id'      => $this->_request['store_id'],
                    'wholesaler_id' => $this->_wholesalerIds
                ];
                $activity = (new PintuanActivityDetailProxy('product', 'pintuan.PintuanActivityDetail', $request))
                    ->sendRequest()->toArray();
                if (isset($activity['activity']) && $activity['activity']) {
                    $data[] = $topic;
                }
            } elseif ($topic['type'] == 1) {//商品
                $params['wholesaler_ids'] = $this->_wholesalerIds;
                $params['topic_id'] = $topic['id'];
                $result = (new ProductListProxy('product', 'product.topicProductList', $params))->sendRequest()->toArray();
                if (isset($result['product_list'])) {
                    $data[] = $topic;
                }
            }
        }
        return $data;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}