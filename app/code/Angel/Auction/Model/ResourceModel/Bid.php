<?php


namespace Angel\Auction\Model\ResourceModel;

class Bid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Auction Bid relation table name
     */
    const TABLE_NAME = 'angel_auction_bid';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'bid_id');
    }
}
