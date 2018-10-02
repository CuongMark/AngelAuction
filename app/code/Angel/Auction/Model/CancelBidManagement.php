<?php


namespace Angel\Auction\Model;

class CancelBidManagement implements Angel\Auction\Api\CancelBidManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postCancelBid($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}
