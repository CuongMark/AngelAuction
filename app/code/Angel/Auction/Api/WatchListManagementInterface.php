<?php


namespace Angel\Auction\Api;

interface WatchListManagementInterface
{

    /**
     * POST for watchList api
     * @param string $param
     * @return string
     */
    public function postWatchList($param);
}
