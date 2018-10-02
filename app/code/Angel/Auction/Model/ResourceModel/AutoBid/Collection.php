<?php


namespace Angel\Auction\Model\ResourceModel\AutoBid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Angel\Auction\Model\AutoBid::class,
            \Angel\Auction\Model\ResourceModel\AutoBid::class
        );
    }
}
