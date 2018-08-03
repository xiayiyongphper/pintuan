<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use message\user\getRandProductResponse;
use message\user\getRandUserResponse;
use message\user\UserBreif;
use service\callService\product\getRandProductProxy;
use service\callService\user\getRandUserProxy;

class getBuyingToast extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $location = isset($this->_request['location']) ? $this->_request['location'] : 0;
        /** @var getRandUserResponse $users */
        $users = (new getRandUserProxy('user', 'user.getRandUser', $this->_request))->sendRequest();
        $users = $users->getUsers();
        //1:首页 2:普通商品详情 3:拼团商品详情 4:接龙商品详情
        switch ($location) {
            case 1://首页
                $result = $this->getHomeBuyingToast($users);
                break;
            case 2:
            case 3:
            case 4:
                $result = $this->getCommonBuyingToast($users);
                break;
            default:
                $result = $this->getHomeBuyingToast($users);
                break;
        }

        return $result;
    }

    protected function getHomeBuyingToast($users)
    {
        $result = [];
        /** @var getRandProductResponse $products */
        $products = (new getRandProductProxy('product', 'product.getRandProduct', $this->_request))->sendRequest();
        $productList = $products->getProductList();

        /** @var UserBreif $user */
        foreach ($users as $key => $user) {
            $toast['nick_name'] = $user->getNickName();
            $toast['avatar_url'] = $user->getAvatarUrl();
            $toast['action'] = '购买了';
            if (!isset($productList[$key])) {
                continue;
            }
            $product = $productList[$key];
            $toast['product'] = $product->getName();
            $result[] = $toast;
        }
        return $result;
    }

    protected function getCommonBuyingToast($users)
    {
        $result = [];
        /** @var UserBreif $user */
        foreach ($users as $user) {
            $toast['nick_name'] = $user->getNickName();
            $toast['avatar_url'] = $user->getAvatarUrl();
            $toast['action'] = '购买了';
            $toast['product'] = '该商品';
            $result[] = $toast;
        }
        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['location', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}