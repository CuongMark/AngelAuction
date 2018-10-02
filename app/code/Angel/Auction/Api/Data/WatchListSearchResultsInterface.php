<?php


namespace Angel\Auction\Api\Data;

interface WatchListSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get WatchList list.
     * @return \Angel\Auction\Api\Data\WatchListInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\Auction\Api\Data\WatchListInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
