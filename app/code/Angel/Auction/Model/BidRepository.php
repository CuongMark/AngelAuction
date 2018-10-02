<?php


namespace Angel\Auction\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Angel\Auction\Api\Data\BidSearchResultsInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Angel\Auction\Model\ResourceModel\Bid\CollectionFactory as BidCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Angel\Auction\Model\ResourceModel\Bid as ResourceBid;
use Angel\Auction\Api\BidRepositoryInterface;
use Angel\Auction\Api\Data\BidInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;

class BidRepository implements BidRepositoryInterface
{

    protected $searchResultsFactory;

    protected $resource;

    protected $dataBidFactory;

    private $storeManager;
    protected $dataObjectProcessor;

    protected $bidCollectionFactory;

    protected $dataObjectHelper;

    protected $bidFactory;


    /**
     * @param ResourceBid $resource
     * @param BidFactory $bidFactory
     * @param BidInterfaceFactory $dataBidFactory
     * @param BidCollectionFactory $bidCollectionFactory
     * @param BidSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceBid $resource,
        BidFactory $bidFactory,
        BidInterfaceFactory $dataBidFactory,
        BidCollectionFactory $bidCollectionFactory,
        BidSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->bidFactory = $bidFactory;
        $this->bidCollectionFactory = $bidCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBidFactory = $dataBidFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Angel\Auction\Api\Data\BidInterface $bid
    ) {
        /* if (empty($bid->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $bid->setStoreId($storeId);
        } */
        try {
            $this->resource->save($bid);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the bid: %1',
                $exception->getMessage()
            ));
        }
        return $bid;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($bidId)
    {
        $bid = $this->bidFactory->create();
        $this->resource->load($bid, $bidId);
        if (!$bid->getId()) {
            throw new NoSuchEntityException(__('Bid with id "%1" does not exist.', $bidId));
        }
        return $bid;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->bidCollectionFactory->create();
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
        \Angel\Auction\Api\Data\BidInterface $bid
    ) {
        try {
            $this->resource->delete($bid);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Bid: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($bidId)
    {
        return $this->delete($this->getById($bidId));
    }
}
