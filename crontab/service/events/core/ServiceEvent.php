<?php
namespace service\events\core;

use service\message\customer\CustomerResponse;
use yii\base\Event;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/29
 * Time: 15:31
 */
class ServiceEvent extends Event
{
    /**
     * @var array
     */
    protected $_eventData;
    protected $_traceId;
    /**
     * @var CustomerResponse
     */
    protected $_customer;
    const SALES_QUOTE_SUBMIT_BEFORE = 'sales_model_quote_submit_before';
    const SALES_QUOTE_SUBMIT_FAILURE = 'sales_model_quote_submit_failure';
    const SALES_ORDER_PLACE_AFTER = 'sales_order_place_after';


    /**
     * @return array
     */
    public function getEventData()
    {
        return $this->_eventData;
    }

    /**
     * @param array $eventData
     */
    public function setEventData($eventData)
    {
        $this->_eventData = $eventData;
    }

    /**
     * @return mixed
     */
    public function getTraceId()
    {
        return $this->_traceId;
    }

    /**
     * @param mixed $traceId
     */
    public function setTraceId($traceId)
    {
        $this->_traceId = $traceId;
    }

    /**
     * @return CustomerResponse
     */
    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * @param CustomerResponse $customer
     */
    public function setCustomer($customer)
    {
        $this->_customer = $customer;
    }
}