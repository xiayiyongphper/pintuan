<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * WalletRecordListRes message
 */
class WalletRecordListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wallet_info = 1;
    const pagination = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wallet_info => array(
            'name' => 'wallet_info',
            'repeated' => true,
            'type' => '\message\store\WalletRecord'
        ),
        self::pagination => array(
            'name' => 'pagination',
            'required' => false,
            'type' => '\message\common\Pagination'
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
        $this->values[self::wallet_info] = array();
        $this->values[self::pagination] = null;
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
     * Appends value to 'wallet_info' list
     *
     * @param \message\store\WalletRecord $value Value to append
     *
     * @return null
     */
    public function appendWalletInfo(\message\store\WalletRecord $value)
    {
        return $this->append(self::wallet_info, $value);
    }

    /**
     * Clears 'wallet_info' list
     *
     * @return null
     */
    public function clearWalletInfo()
    {
        return $this->clear(self::wallet_info);
    }

    /**
     * Returns 'wallet_info' list
     *
     * @return \message\store\WalletRecord[]
     */
    public function getWalletInfo()
    {
        return $this->get(self::wallet_info);
    }

    /**
     * Returns 'wallet_info' iterator
     *
     * @return \ArrayIterator
     */
    public function getWalletInfoIterator()
    {
        return new \ArrayIterator($this->get(self::wallet_info));
    }

    /**
     * Returns element from 'wallet_info' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\store\WalletRecord
     */
    public function getWalletInfoAt($offset)
    {
        return $this->get(self::wallet_info, $offset);
    }

    /**
     * Returns count of 'wallet_info' list
     *
     * @return int
     */
    public function getWalletInfoCount()
    {
        return $this->count(self::wallet_info);
    }

    /**
     * Sets value of 'pagination' property
     *
     * @param \message\common\Pagination $value Property value
     *
     * @return null
     */
    public function setPagination(\message\common\Pagination $value=null)
    {
        return $this->set(self::pagination, $value);
    }

    /**
     * Returns value of 'pagination' property
     *
     * @return \message\common\Pagination
     */
    public function getPagination()
    {
        return $this->get(self::pagination);
    }
}