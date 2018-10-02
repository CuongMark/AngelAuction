<?php


namespace Angel\Auction\Model;

class AutoBidManagement implements Angel\Auction\Api\AutoBidManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postAutoBid($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}
