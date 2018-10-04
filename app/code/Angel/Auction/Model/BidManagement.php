<?php


namespace Angel\Auction\Model;

class BidManagement implements \Angel\Auction\Api\BidManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postBid($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}
