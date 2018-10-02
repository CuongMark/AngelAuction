<?php


namespace Angel\Auction\Api\Data;

interface BidSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Bid list.
     * @return \Angel\Auction\Api\Data\BidInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\Auction\Api\Data\BidInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
