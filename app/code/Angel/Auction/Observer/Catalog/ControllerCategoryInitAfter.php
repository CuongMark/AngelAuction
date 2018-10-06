<?php


namespace Angel\Auction\Observer\Catalog;

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
        $products = $category->getProductCollection();
        /** @var \Magento\Catalog\Model\Product $_product */
        foreach ($products as $_product){
            if ($_product->getTypeId() == \Angel\Auction\Model\Product\Type::TYPE_CODE){
                $this->auctionFactory->create()->init($_product)->updateStatus();
            }
        }
    }
}
