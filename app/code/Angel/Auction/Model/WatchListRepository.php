<?php


namespace Angel\Auction\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;
use Angel\Auction\Model\ResourceModel\WatchList\CollectionFactory as WatchListCollectionFactory;
use Angel\Auction\Api\WatchListRepositoryInterface;
use Angel\Auction\Api\Data\WatchListInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Angel\Auction\Api\Data\WatchListSearchResultsInterfaceFactory;
use Angel\Auction\Model\ResourceModel\WatchList as ResourceWatchList;

class WatchListRepository implements WatchListRepositoryInterface
{

    protected $resource;

    protected $watchListCollectionFactory;

    protected $watchListFactory;

    protected $dataWatchListFactory;

    private $storeManager;
    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $searchResultsFactory;


    /**
     * @param ResourceWatchList $resource
     * @param WatchListFactory $watchListFactory
     * @param WatchListInterfaceFactory $dataWatchListFactory
     * @param WatchListCollectionFactory $watchListCollectionFactory
     * @param WatchListSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWatchList $resource,
        WatchListFactory $watchListFactory,
        WatchListInterfaceFactory $dataWatchListFactory,
        WatchListCollectionFactory $watchListCollectionFactory,
        WatchListSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->watchListFactory = $watchListFactory;
        $this->watchListCollectionFactory = $watchListCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWatchListFactory = $dataWatchListFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Angel\Auction\Api\Data\WatchListInterface $watchList
    ) {
        /* if (empty($watchList->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $watchList->setStoreId($storeId);
        } */
        try {
            $this->resource->save($watchList);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the watchList: %1',
                $exception->getMessage()
            ));
        }
        return $watchList;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($watchListId)
    {
        $watchList = $this->watchListFactory->create();
        $this->resource->load($watchList, $watchListId);
        if (!$watchList->getId()) {
            throw new NoSuchEntityException(__('WatchList with id "%1" does not exist.', $watchListId));
        }
        return $watchList;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->watchListCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $fields[] = $filter->getField();
                $condition = $filter->getConditionType() ?: 'eq';
                $conditions[] = [$condition => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Angel\Auction\Api\Data\WatchListInterface $watchList
    ) {
        try {
            $this->resource->delete($watchList);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the WatchList: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($watchListId)
    {
        return $this->delete($this->getById($watchListId));
    }
}
