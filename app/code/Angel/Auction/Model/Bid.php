<?php


namespace Angel\Auction\Model;

use Angel\Auction\Api\Data\BidInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;

class Bid extends \Magento\Framework\Model\AbstractModel implements BidInterface
{
    const BID_PENDING = 0;
    const BID_WON = 1;
    const BID_LOSE = 2;
    const BID_BOUGHT = 3;
    const BID_CANCELED = 4;

    protected $_eventPrefix = 'angel_auction_bid';

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ){
        $this->customerRepository = $customerRepository;
        $this->_customerFactory = $customerFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Angel\Auction\Model\ResourceModel\Bid::class);
    }

    public function getStatusLabel(){
        switch ($this->getStatus()){
            case static::BID_PENDING:
                return __('Pending');
            case static::BID_WON:
                return __('Won');
            case static::BID_LOSE:
                return __('Lose');
            case static::BID_BOUGHT:
                return __('Bought');
            case static::BID_CANCELED:
                return __('Won');
            default:
                return __('Pending');
        }
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer(){
        if (!isset($this->customer) && $this->getCustomerId()){
            $this->customer = $this->_customerFactory->create()->load($this->getCustomerId());
        }
        return $this->customer;
    }

    /**
     * Get bid_id
     * @return string
     */
    public function getBidId()
    {
        return $this->getData(self::BID_ID);
    }

    /**
     * Set bid_id
     * @param string $bidId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setBidId($bidId)
    {
        return $this->setData(self::BID_ID, $bidId);
    }

    /**
     * Get product_id
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Get customer_id
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get order_id
     * @return string
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set order_id
     * @param string $orderId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get autobid_id
     * @return string
     */
    public function getAutobidId()
    {
        return $this->getData(self::AUTOBID_ID);
    }

    /**
     * Set autobid_id
     * @param string $autobidId
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setAutobidId($autobidId)
    {
        return $this->setData(self::AUTOBID_ID, $autobidId);
    }

    /**
     * Get price
     * @return string
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * Set price
     * @param string $price
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Get created_time
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->getData(self::CREATED_TIME);
    }

    /**
     * Set created_time
     * @param string $createdTime
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setCreatedTime($createdTime)
    {
        return $this->setData(self::CREATED_TIME, $createdTime);
    }

    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
