<?php


namespace Angel\Auction\Model;

use Magento\Framework\Exception\NoSuchEntityException;

class BidManagement implements \Angel\Auction\Api\BidManagementInterface
{
    /**
     * @var Auction
     */
    protected $auction;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Customer\Model\ResourceModel\CustomerRepository
     */
    protected $customerRepository;

    /**
     * BidManagement constructor.
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param Auction $auction
     * @param \Magento\Customer\Model\ResourceModel\CustomerRepository $customerRepository
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        Auction $auction,
        \Magento\Customer\Model\ResourceModel\CustomerRepository $customerRepository
    ){
        $this->productRepository = $productRepository;
        $this->auction = $auction;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param \Angel\Auction\Api\Data\BidInterface $bid
     * @return \Angel\Auction\Model\Auction
     */
    public function getAuction($bid){
        try {
            $auction = $this->auction->init($this->productRepository->getById($bid->getProductId()));
            return $auction;
        } catch (NoSuchEntityException $e){}
        return null;
    }

    /**
     * @param \Angel\Auction\Api\Data\BidInterface $bid
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomer($bid){
        return $this->customerRepository->getById($bid->getCustomerId());
    }
}
