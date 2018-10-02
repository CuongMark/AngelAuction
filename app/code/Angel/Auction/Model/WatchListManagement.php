<?php


namespace Angel\Auction\Model;

class WatchListManagement implements Angel\Auction\Api\WatchListManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postWatchList($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}
