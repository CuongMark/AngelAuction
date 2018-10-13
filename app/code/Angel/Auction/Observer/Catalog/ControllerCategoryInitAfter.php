<?php


namespace Angel\Auction\Observer\Catalog;

use Angel\Auction\Model\Auction;
use Angel\Auction\Model\Product\Attribute\Source\Status;

class ControllerCategoryInitAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Angel\Auction\Model\AuctionFactory
     */
    protected $auctionFactory;

    /**
     * ActionCatalogProductSaveEntityAfter constructor.
     * @param \Angel\Auction\Model\Auction $auction
     */
    public function __construct(
        \Angel\Auction\Model\AuctionFactory $auctionFactory
    ){
        $this->auctionFactory = $auctionFactory;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $observer->getEvent()->getCategory();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products */
        $products = clone $category->getProductCollection()
            ->addAttributeToSelect([Auction::START_TIME_FIELD, Auction::END_TIME_FIELD, Auction::STATUS_FIELD ])
            ->addAttributeToFilter(Auction::STATUS_FIELD,['in' => [Status::NOT_START, Status::PROCESSING]]);
        /** @var \Magento\Catalog\Model\Product $_product */
        foreach ($products as $_product){
            if ($_product->getTypeId() == \Angel\Auction\Model\Product\Type::TYPE_CODE){
                $this->auctionFactory->create()->init($_product)->updateStatus();
            }
        }
    }
}
