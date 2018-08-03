<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderVerificationSaveRes message
 */
class orderVerificationSaveRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order = 1;
    const commission = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order => array(
            'name' => 'order',
            'required' => true,
            'type' => '\message\order\order'
        ),
        self::commission => array(
            'name' => 'commission',
            'required' => false,
            'type' => '\message\order\commissionRecord'
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::order] = null;
        $this->values[self::commission] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'order' property
     *
     * @param \message\order\order $value Property value
     *
     * @return null
     */
    public function setOrder(\message\order\order $value=null)
    {
        return $this->set(self::order, $value);
    }

    /**
     * Returns value of 'order' property
     *
     * @return \message\order\order
     */
    public function getOrder()
    {
        return $this->get(self::order);
    }

    /**
     * Sets value of 'commission' property
     *
     * @param \message\order\commissionRecord $value Property value
     *
     * @return null
     */
    public function setCommission(\message\order\commissionRecord $value=null)
    {
        return $this->set(self::commission, $value);
    }

    /**
     * Returns value of 'commission' property
     *
     * @return \message\order\commissionRecord
     */
    public function getCommission()
    {
        return $this->get(self::commission);
    }
}