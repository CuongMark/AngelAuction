<?php


namespace Angel\Auction\Api\Data;

interface WatchListInterface
{

    const CUSTOMER_ID = 'customer_id';
    const WATCHLIST_ID = 'watchlist_id';
    const PRODUCT_ID = 'product_id';

    /**
     * Get watchlist_id
     * @return string|null
     */
    public function getWatchlistId();

    /**
     * Set watchlist_id
     * @param string $watchlistId
     * @return \Angel\Auction\Api\Data\WatchListInterface
     */
    public function setWatchlistId($watchlistId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Auction\Api\Data\WatchListInterface
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
     * @return \Angel\Auction\Api\Data\WatchListInterface
     */
    public function setCustomerId($customerId);
}
