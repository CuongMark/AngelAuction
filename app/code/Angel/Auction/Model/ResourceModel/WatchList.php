<?php


namespace Angel\Auction\Model\ResourceModel;

class WatchList extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Auction WatchList relation table name
     */
    const TABLE_NAME = 'angel_auction_watchlist';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'watchlist_id');
    }
}
