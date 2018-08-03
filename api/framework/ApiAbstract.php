<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/12
 * Time: 15:12
 */

namespace framework;

use message\store\Store;
use service\callService\product\GetNewUserActivityProxy;
use service\callService\store\GetStoreDetailProxy;
use service\callService\store\GetWholesalerDistrictListProxy;
use service\callService\store\StoreLoginProxy;
use message\user\UserResponse;
use service\callService\user\UserProxy;

/**
 * Class ApiAbstract
 * @package framework
 */
abstract class ApiAbstract
{
    protected $_request;
    protected $_result = [];
    /** @var  UserResponse $_user */
    protected $_user;
    protected $_store;
    protected $_wholesalerIds;//当前自提点供货商id数组

    abstract public function run($params);

    /**
     * 定义参数校验规则，返回规则数组
     */
    abstract protected function getRules();

    protected function doInit($data, $checkUser = true, $type = 1)
    {
        Tool::log($data, 'params.log');
        $this->checkParams($data);
        if ($type == 1) {
            $checkUser && $this->initUser();
        } else {
            $checkUser && $this->initStore();
        }
    }

    /**
     * @param array $data json格式的参数
     */
    protected function checkParams($data)
    {
        $this->_request = (new validParam($data, $this->getRules()))->check();
    }

    protected function initUser()
    {
        if (!isset($this->_request['user_id']) || !isset($this->_request['auth_token'])) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        $this->_user = (new UserProxy('user', 'user.userAuthentication',
            ['user_id' => $this->_request['user_id'], 'auth_token' => $this->_request['auth_token']]
        ))->sendRequest();

        if (isset($this->_request['store_id'])) {
            $this->initWholesalersIdByStoreId();
        }
    }

    /**
     * @ 商家端验证登录
     * @param array $data json格式的参数
     */
    protected function initStore()
    {
//        if (!isset($this->_request['store_id']) || !isset($this->_request['auth_token'])) {
//            Exception::throwException(Exception::INVALID_PARAM);
//        }
//        $this->_store = (new StoreLoginProxy('store', 'store.storeAuthentication',
//            ['store_id' => $this->_request['store_id'], 'auth_token' => $this->_request['auth_token']]
//        ))->sendRequest();

//        unset($this->_request['store_id']);
        unset($this->_request['auth_token']);
    }

    protected function initWholesalersIdByStoreId($storeId = 0)
    {
        $storeId && $this->_request['store_id'] = $storeId;
        $this->_wholesalerIds = GetWholesalerDistrictListProxy::getWholesalersByStore($this->_request['store_id']);
    }

    protected function getNewUserActivity()
    {
        $activityId = 0;
        if ($this->_user->getHasOrder() == 2) {
            if (empty($this->_wholesalerIds))
                return $activityId;

            /** @var Store $store */
            $store = (new GetStoreDetailProxy(['store_id' => $this->_request['store_id']]))->sendRequest();
            $params = [
                'store_id'      => $this->_request['store_id'],
                'wholesaler_id' => $this->_wholesalerIds,
                'city'          => $store->getCity()
            ];
            $activity = (new GetNewUserActivityProxy($params))->sendRequest()->toArray();

            if (isset($activity['activity_id'])) {
                $activityId = $activity['activity_id'];
            }
        }
        return $activityId;
    }

}