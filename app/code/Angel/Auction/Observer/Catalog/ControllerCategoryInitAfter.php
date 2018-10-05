<?php


namespace Angel\Auction\Observer\Catalog;

class ControllerCategoryInitAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
//        /** @var \Magento\Catalog\Model\Category $category */
//        $category = $observer->getEvent()->getCategory();
//        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products */
//        $products = $category->getProductCollection();
//        /** @var \Magento\Catalog\Model\Product $_product */
//        foreach ($products as $_product){
//            if ($_product->getTypeId() == \Angel\RaffleClient\Model\Fifty::TYPE_ID){
//                $this->raffleFactory->create()->setProduct($_product->getId())->generateFiftyRaffleTicket();
//            }
//        }
    }
}
