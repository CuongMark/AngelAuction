<?php


namespace Angel\Auction\Api;

interface RemoveWatchListManagementInterface
{

    /**
     * POST for removeWatchList api
     * @param string $param
     * @return string
     */
    public function postRemoveWatchList($param);
}
