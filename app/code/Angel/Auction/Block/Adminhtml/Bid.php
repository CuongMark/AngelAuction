<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Auction\Block\Adminhtml;

use Angel\Auction\Model\BidRepository;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Obtain all carts contents for specified client
 *
 * @api
 * @since 100.0.2
 */
class Bid extends \Magento\Backend\Block\Template
{
    /**
     * @var BidRepository
     */
    protected $bidRespository;

    /**
     * @var \Angel\Auction\Model\BidManagement
     */
    protected $bidManagement;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Bid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param BidRepository $bidRepository
     * @param \Angel\Auction\Model\BidManagement $bidManagement
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        BidRepository $bidRepository,
        \Angel\Auction\Model\BidManagement $bidManagement,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->bidRespository = $bidRepository;
        $this->bidManagement = $bidManagement;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    /**
     * @return \Angel\Auction\Api\Data\BidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBid(){
        if (!isset($this->bid)){
            $this->bid = $this->bidRespository->getById($this->_request->getParam('id'));
        }
        return $this->bid;
    }

    /**
     * @return \Angel\Auction\Model\Auction
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAuction(){
        if (!isset($this->auction)){
            $this->auction = $this->bidManagement->getAuction($this->getBid());
        }
        return $this->auction;
    }

    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomer(){
        if (!isset($this->customer)){
            $this->customer = $this->bidManagement->getCustomer($this->getBid());
        }
        return $this->customer;
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value, $isHtml = true)
    {
        return $this->priceCurrency->format(
            $value,
            $isHtml,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            1 //Todo getStore
        );
    }
}
