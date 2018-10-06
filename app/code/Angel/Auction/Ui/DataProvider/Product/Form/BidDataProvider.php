<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Auction\Ui\DataProvider\Product\Form;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Cache\Frontend\Adapter\Zend;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Angel\Auction\Model\ResourceModel\Bid\CollectionFactory;
use Angel\Auction\Model\ResourceModel\Bid\Collection;
use Magento\Review\Model\Review;

/**
 * Class ReviewDataProvider
 *
 * @api
 *
 * @method Collection getCollection
 * @since 100.1.0
 */
class BidDataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     * @since 100.1.0
     */
    protected $collectionFactory;

    /**
     * @var RequestInterface
     * @since 100.1.0
     */
    protected $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
        $this->collection = $this->collectionFactory->create();
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     * @since 100.1.0
     */
    public function getData()
    {
        if ($this->request->getParam('current_product_id', 0))
            $this->getCollection()->addFieldToFilter(\Angel\Auction\Model\Bid::PRODUCT_ID, $this->request->getParam('current_product_id', 0));

        $this->getCollection()->getSelect()->joinLeft(
            ['customer' => $this->getCollection()->getTable('customer_entity')],
            'main_table.customer_id = customer.entity_id',
            ['customer_email' => 'customer.email']
        );
        $this->getCollection()->addOrder('price');
        $arrItems = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => [],
        ];

        foreach ($this->getCollection() as $item) {
            $arrItems['items'][] = $item->toArray([]);
        }
        return $arrItems;
    }

    /**
     * {@inheritdoc}
     * @since 100.1.0
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        parent::addFilter($filter);
    }
}
