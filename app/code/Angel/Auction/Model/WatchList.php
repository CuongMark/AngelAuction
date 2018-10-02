<?php


namespace Angel\Auction\Model;

use Angel\Auction\Api\Data\WatchListInterface;

class WatchList extends \Magento\Framework\Model\AbstractModel implements WatchListInterface
{

    protected $_eventPrefix = 'angel_auction_watchlist';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Angel\Auction\Model\ResourceModel\WatchList::class);
    }

    /**
     * Get watchlist_id
     * @return string
     */
    public function getWatchlistId()
    {
        return $this->getData(self::WATCHLIST_ID);
    }

    /**
     * Set watchlist_id
     * @param string $watchlistId
     * @return \Angel\Auction\Api\Data\WatchListInterface
     */
    public function setWatchlistId($watchlistId)
    {
        return $this->setData(self::WATCHLIST_ID, $watchlistId);
    }

    /**
     * Get product_id
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Auction\Api\Data\WatchListInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Get customer_id
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Angel\Auction\Api\Data\WatchListInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }
}
