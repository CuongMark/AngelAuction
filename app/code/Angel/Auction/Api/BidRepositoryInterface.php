<?php


namespace Angel\Auction\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface BidRepositoryInterface
{

    /**
     * Save Bid
     * @param \Angel\Auction\Api\Data\BidInterface $bid
     * @return \Angel\Auction\Api\Data\BidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Auction\Api\Data\BidInterface $bid
    );

    /**
     * Retrieve Bid
     * @param string $bidId
     * @return \Angel\Auction\Api\Data\BidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($bidId);

    /**
     * Retrieve Bid matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Auction\Api\Data\BidSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Bid
     * @param \Angel\Auction\Api\Data\BidInterface $bid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Auction\Api\Data\BidInterface $bid
    );

    /**
     * Delete Bid by ID
     * @param string $bidId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($bidId);
}
