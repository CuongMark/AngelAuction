<?php


namespace Angel\Auction\Api\Data;

interface BidInterface
{

    const AUTOBID_ID = 'autobid_id';
    const PRICE = 'price';
    const ORDER_ID = 'order_id';
    const CUSTOMER_ID = 'customer_id';
    const CREATED_TIME = 'created_time';
    const PRODUCT_ID = 'product_id';
    const BID_ID = 'bid_id';
    const STATUS = 'status';

    /**
     * Get bid_id
     * @return string|null
     */
    public function getBidId();

    /**
     * Set bid_id
     * @param string $bidId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setBidId($bidId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setProductId($productId);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $orderId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setOrderId($orderId);

    /**
     * Get autobid_id
     * @return string|null
     */
    public function getAutobidId();

    /**
     * Set autobid_id
     * @param string $autobidId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setAutobidId($autobidId);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setPrice($price);

    /**
     * Get created_time
     * @return string|null
     */
    public function getCreatedTime();

    /**
     * Set created_time
     * @param string $createdTime
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setCreatedTime($createdTime);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setStatus($status);
}
