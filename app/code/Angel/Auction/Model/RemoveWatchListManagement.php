<?php


namespace Angel\Auction\Model;

class RemoveWatchListManagement implements Angel\Auction\Api\RemoveWatchListManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postRemoveWatchList($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}
