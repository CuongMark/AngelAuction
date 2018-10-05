<?php

namespace Angel\Auction\Model\ResourceModel;

class AutoBid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Auction Autobid relation table name
     */
    const TABLE_NAME = 'angel_auction_autobid';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'autobid_id');
    }
}
