<?php


namespace Angel\Auction\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WatchListRepositoryInterface
{

    /**
     * Save WatchList
     * @param \Angel\Auction\Api\Data\WatchListInterface $watchList
     * @return \Angel\Auction\Api\Data\WatchListInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Auction\Api\Data\WatchListInterface $watchList
    );

    /**
     * Retrieve WatchList
     * @param string $watchlistId
     * @return \Angel\Auction\Api\Data\WatchListInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($watchlistId);

    /**
     * Retrieve WatchList matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Auction\Api\Data\WatchListSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete WatchList
     * @param \Angel\Auction\Api\Data\WatchListInterface $watchList
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Auction\Api\Data\WatchListInterface $watchList
    );

    /**
     * Delete WatchList by ID
     * @param string $watchlistId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($watchlistId);
}
