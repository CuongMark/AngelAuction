<?php


namespace Angel\Auction\Api;

interface AutoBidManagementInterface
{

    /**
     * POST for autoBid api
     * @param string $param
     * @return string
     */
    public function postAutoBid($param);
}
