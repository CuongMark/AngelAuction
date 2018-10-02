<?php


namespace Angel\Auction\Api\Data;

interface AutoBidInterface
{

    const AUTOBID_ID = 'autobid_id';
    const PRICE = 'price';
    const CUSTOMER_ID = 'customer_id';
    const CREATED_TIME = 'created_time';
    const PRODUCT_ID = 'product_id';
    const STATUS = 'status';

    /**
     * Get autobid_id
     * @return string|null
     */
    public function getAutobidId();

    /**
     * Set autobid_id
     * @param string $autobidId
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setAutobidId($autobidId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Auction\Api\Data\AutoBidInterface
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
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Angel\Auction\Api\Data\AutoBidInterface
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
     * @return \Angel\Auction\Api\Data\AutoBidInterface
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
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function setStatus($status);
}
