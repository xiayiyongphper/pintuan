<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * WalletRecordRes message
 */
class WalletRecordRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wallet_record = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wallet_record => array(
            'name' => 'wallet_record',
            'required' => false,
            'type' => '\message\store\WalletRecord'
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
        $this->values[self::wallet_record] = null;
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
     * Sets value of 'wallet_record' property
     *
     * @param \message\store\WalletRecord $value Property value
     *
     * @return null
     */
    public function setWalletRecord(\message\store\WalletRecord $value=null)
    {
        return $this->set(self::wallet_record, $value);
    }

    /**
     * Returns value of 'wallet_record' property
     *
     * @return \message\store\WalletRecord
     */
    public function getWalletRecord()
    {
        return $this->get(self::wallet_record);
    }
}