<?php

namespace Angel\Auction\Ui\DataProvider\Product;

use Angel\Auction\Model\ResourceModel\Bid;

/**
 * Class ReviewDataProvider
 *
 * @api
 *
 * @method \Magento\Catalog\Model\ResourceModel\Product\Collection getCollection
 * @since 100.1.0
 */
class AuctionDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->getCollection()->addAttributeToFilter('type_id', ['in' => [\Angel\Auction\Model\Product\Type::TYPE_CODE]]);
        $this->getCollection()->addAttributeToSelect(['auction_start_time', 'auction_end_time']);
        $this->getCollection()->getSelect()->joinLeft(
            ['bid' => $this->getCollection()->getTable(Bid::TABLE_NAME)],
            'e.entity_id = bid.product_id && bid.'.\Angel\Auction\Model\Bid::STATUS .' = '.\Angel\Auction\Model\Bid::BID_WON,
            ['winning_price' => 'bid.'.\Angel\Auction\Model\Bid::PRICE]
        );
        $this->getCollection()->getSelect()->joinLeft(
            ['customer' => $this->getCollection()->getTable('customer_entity')],
            'customer.entity_id = bid.'.\Angel\Auction\Model\Bid::CUSTOMER_ID,
            ['winner_email' => 'customer.email']
        );
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection()->toArray();

        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];
    }
}
