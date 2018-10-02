<?php


namespace Angel\Auction\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AutoBidRepositoryInterface
{

    /**
     * Save AutoBid
     * @param \Angel\Auction\Api\Data\AutoBidInterface $autoBid
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Auction\Api\Data\AutoBidInterface $autoBid
    );

    /**
     * Retrieve AutoBid
     * @param string $autobidId
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($autobidId);

    /**
     * Retrieve AutoBid matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Auction\Api\Data\AutoBidSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete AutoBid
     * @param \Angel\Auction\Api\Data\AutoBidInterface $autoBid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Auction\Api\Data\AutoBidInterface $autoBid
    );

    /**
     * Delete AutoBid by ID
     * @param string $autobidId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($autobidId);
}
