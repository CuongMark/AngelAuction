<?php


namespace Angel\Auction\Model;

use Angel\Auction\Api\Data\AutoBidInterface;

class AutoBid extends \Magento\Framework\Model\AbstractModel implements AutoBidInterface
{

    protected $_eventPrefix = 'angel_auction_autobid';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Angel\Auction\Model\ResourceModel\AutoBid::class);
    }

    /**
     * Get autobid_id
     * @return string
     */
    public function getAutobidId()
    {
        return $this->getData(self::AUTOBID_ID);
    }

    /**
     * Set autobid_id
     * @param string $autobidId
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setAutobidId($autobidId)
    {
        return $this->setData(self::AUTOBID_ID, $autobidId);
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
     * @return \Angel\Auction\Api\Data\AutoBidInterface
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
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get price
     * @return string
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * Set price
     * @param string $price
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Get created_time
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->getData(self::CREATED_TIME);
    }

    /**
     * Set created_time
     * @param string $createdTime
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setCreatedTime($createdTime)
    {
        return $this->setData(self::CREATED_TIME, $createdTime);
    }

    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
