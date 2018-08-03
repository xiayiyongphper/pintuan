<?php
namespace framework\data;
use yii\base\Object;

/**
 * Data collection
 *
 * @category    Varien
 * @package     Varien_Data
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Pagination extends Object
{
    const SORT_ORDER_ASC = 'ASC';
    const SORT_ORDER_DESC = 'DESC';

    /**
     * Current page number for items pager
     *
     * @var int
     */
    protected $_curPage = 1;

    /**
     * Pager page size
     *
     * if page size is false, then we works with all items
     *
     * @var int | false
     */
    protected $_pageSize = false;

    /**
     * Total items number
     *
     * @var int
     */
    public $totalCount = 0;

    /**
     * Get current collection page
     *
     * @param  int $displacement
     * @return int
     */
    public function getCurPage($displacement = 0)
    {
        if ($this->_curPage + $displacement < 1) {
            return 1;
        } elseif ($this->_curPage + $displacement > $this->getLastPageNumber()) {
            return $this->getLastPageNumber();
        } else {
            return $this->_curPage + $displacement;
        }
    }

    /**
     * Retrieve collection last page number
     *
     * @return int
     */
    public function getLastPageNumber()
    {
        $collectionSize = (int)$this->getTotalCount();
        if (0 === $collectionSize) {
            return 1;
        } elseif ($this->_pageSize) {
            return ceil($collectionSize / $this->_pageSize);
        } else {
            return 1;
        }
    }

    /**
     * Retrieve collection page size
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /**
     * Retrieve collection all items count
     *
     * @return int
     */
    public function getTotalCount()
    {
        return intval($this->totalCount);
    }

    /**
     * @param $totalCount
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * Set current page
     *
     * @param   int $page
     * @return  $this
     */
    public function setCurPage($page)
    {
        $this->_curPage = $page;
        return $this;
    }

    /**
     * Set collection page size
     *
     * @param   int $size
     * @return  $this
     */
    public function setPageSize($size)
    {
        $this->_pageSize = $size;
        return $this;
    }

    /**
     * @return integer the offset of the data. This may be used to set the
     * OFFSET value for a SQL statement for fetching the current page of data.
     */
    public function getOffset()
    {
        $pageSize = $this->getPageSize();
        return $pageSize < 1 ? 0 : ($this->getCurPage()-1) * $pageSize;
    }

    /**
     * @return integer the limit of the data. This may be used to set the
     * LIMIT value for a SQL statement for fetching the current page of data.
     * Note that if the page size is infinite, a value -1 will be returned.
     */
    public function getLimit()
    {
        $pageSize = $this->getPageSize();

        return $pageSize < 1 ? -1 : $pageSize;
    }

}
