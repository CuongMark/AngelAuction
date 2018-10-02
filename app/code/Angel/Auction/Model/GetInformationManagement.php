<?php


namespace Angel\Auction\Model;

class GetInformationManagement implements Angel\Auction\Api\GetInformationManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function getGetInformation($param)
    {
        return 'hello api GET return the $param ' . $param;
    }
}
