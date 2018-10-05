<?php


namespace Angel\Auction\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;
use Angel\Auction\Api\AutoBidRepositoryInterface;
use Angel\Auction\Model\ResourceModel\AutoBid as ResourceAutoBid;
use Angel\Auction\Model\ResourceModel\AutoBid\CollectionFactory as AutoBidCollectionFactory;
use Magento\Framework\Api\SortOrder;
use Angel\Auction\Api\Data\AutoBidInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Angel\Auction\Api\Data\AutoBidSearchResultsInterfaceFactory;

class AutoBidRepository implements AutoBidRepositoryInterface
{

    protected $autoBidFactory;

    protected $autoBidCollectionFactory;

    protected $resource;

    protected $dataAutoBidFactory;

    private $storeManager;
    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $searchResultsFactory;


    /**
     * @param ResourceAutoBid $resource
     * @param AutoBidFactory $autoBidFactory
     * @param AutoBidInterfaceFactory $dataAutoBidFactory
     * @param AutoBidCollectionFactory $autoBidCollectionFactory
     * @param AutoBidSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceAutoBid $resource,
        AutoBidFactory $autoBidFactory,
        AutoBidInterfaceFactory $dataAutoBidFactory,
        AutoBidCollectionFactory $autoBidCollectionFactory,
        AutoBidSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->autoBidFactory = $autoBidFactory;
        $this->autoBidCollectionFactory = $autoBidCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAutoBidFactory = $dataAutoBidFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Angel\Auction\Api\Data\AutoBidInterface $autoBid
    ) {
        /* if (empty($autoBid->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $autoBid->setStoreId($storeId);
        } */
        try {
            $autoBid->setCreatedTime('2018/10/05 00:00:00');
            $this->resource->save($autoBid);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the autoBid: %1',
                $exception->getMessage()
            ));
        }
        return $autoBid;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($autoBidId)
    {
        $autoBid = $this->autoBidFactory->create();
        $this->resource->load($autoBid, $autoBidId);
        if (!$autoBid->getId()) {
            throw new NoSuchEntityException(__('AutoBid with id "%1" does not exist.', $autoBidId));
        }
        return $autoBid;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->autoBidCollectionFactory->create();
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
        \Angel\Auction\Api\Data\AutoBidInterface $autoBid
    ) {
        try {
            $this->resource->delete($autoBid);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the AutoBid: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($autoBidId)
    {
        return $this->delete($this->getById($autoBidId));
    }
}
