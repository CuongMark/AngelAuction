<?php


namespace Angel\Auction\Observer\Frontend\Catalog;

class ControllerProductInitAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Angel\Auction\Model\Auction
     */
    protected $auction;

    /**
     * ActionCatalogProductSaveEntityAfter constructor.
     * @param \Angel\Auction\Model\Auction $auction
     */
    public function __construct(
        \Angel\Auction\Model\Auction $auction
    ){
        $this->auction = $auction;
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
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();
        $this->auction->updateStatus($product);
    }
}
