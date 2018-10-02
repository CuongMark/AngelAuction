<?php


namespace Angel\Auction\Model\ResourceModel\WatchList;

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
            \Angel\Auction\Model\WatchList::class,
            \Angel\Auction\Model\ResourceModel\WatchList::class
        );
    }
}
