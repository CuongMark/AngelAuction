<?php


namespace Angel\Auction\Api;

interface CancelBidManagementInterface
{

    /**
     * POST for cancelBid api
     * @param string $param
     * @return string
     */
    public function postCancelBid($param);
}
