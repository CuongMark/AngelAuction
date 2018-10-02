<?php


namespace Angel\Auction\Api;

interface BidManagementInterface
{

    /**
     * POST for bid api
     * @param string $param
     * @return string
     */
    public function postBid($param);
}
