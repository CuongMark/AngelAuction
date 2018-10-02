<?php


namespace Angel\Auction\Api\Data;

interface AutoBidSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get AutoBid list.
     * @return \Angel\Auction\Api\Data\AutoBidInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\Auction\Api\Data\AutoBidInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
