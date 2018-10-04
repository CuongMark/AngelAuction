<?php


namespace Angel\Auction\Model;

class Auction
{
    const INIT_PRICE_FIELD = 'auction_init_price';
    const START_TIME_FIELD = 'auction_start_time';
    const END_TIME_FIELD = 'auction_end_time';
    const STATUS_FIELD = 'auction_status';
    const MIN_INTERVAL_FIELD = 'auction_min_interval';
    const MAX_INTERVAL_FIELD = 'auction_max_interval';

    /** @var  \Magento\Catalog\Model\Product */
    protected $product;

    /**
     * @var BidFactory
     */
    protected $bidFactory;

    /**
     * @var BidRepository
     */
    protected $bidRepository;

    /**
     * @var ResourceModel\Bid\CollectionFactory
     */
    protected $bidCollectionFactory;

    /**
     * @var BidFactory
     */
    protected $autoBidFactory;

    /**
     * @var BidRepository
     */
    protected $autoBidRepository;

    /**
     * @var ResourceModel\Bid\CollectionFactory
     */
    protected $autoBidCollectionFactory;

    public function __construct(
        \Angel\Auction\Model\ResourceModel\Bid\CollectionFactory $bidCollectionFactory,
        \Angel\Auction\Model\BidFactory $bidFactory,
        \Angel\Auction\Model\BidRepository $bidRepository,
        \Angel\Auction\Model\ResourceModel\AutoBid\CollectionFactory $autoBidCollectionFactory,
        \Angel\Auction\Model\AutoBidFactory $autoBidFactory,
        \Angel\Auction\Model\AutoBidRepository $autoBidRepository
    ){
        $this->bidCollectionFactory = $bidCollectionFactory;
        $this->bidFactory = $bidFactory;
        $this->bidRepository = $bidRepository;
        $this->autoBidCollectionFactory = $autoBidCollectionFactory;
        $this->autoBidFactory = $autoBidFactory;
        $this->autoBidRepository = $autoBidRepository;
    }

    public function init($product){
        $this->product = $product;
    }


    public function getProduct(){
        return $this->product;
    }

    /**
     * @return boolean
     */
    public function isProcessing(){
        return $this->product->getData(self::STATUS_FIELD) == \Angel\Auction\Model\Product\Attribute\Source\Status::PROCESSING;
    }

    /**
     * @return float
     */
    public function getNextMinBidPrice(){
        return $this->getCurrentBidPrice() + $this->getProduct()->getData(self::MIN_INTERVAL_FIELD);
    }

    /**
     * @return float
     */
    public function getNextMaxBidPrice(){
        return $this->getProduct()->getData(self::MAX_INTERVAL_FIELD) ? $this->getCurrentBidPrice() + $this->getProduct()->getData(self::MAX_INTERVAL_FIELD): 0;
    }

    /**
     * @return float
     */
    public function getCurrentBidPrice(){
        return $this->getLastestBid()->getId()?$this->getLastestBid()->getPrice():$this->getProduct()->getData(self::INIT_PRICE_FIELD);
    }

    /**
     * @return \Angel\Auction\Model\Bid|\Magento\Framework\DataObject
     */
    public function getLastestBid(){
        return $this->getBids($this->product)->getFirstItem();
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \Angel\Auction\Model\ResourceModel\Bid\Collection
     */
    public function getBids($product){
        /** @var \Angel\Auction\Model\ResourceModel\Bid\Collection $bidCollection */
        $bidCollection = $this->bidCollectionFactory->create();
        return $bidCollection->addFieldToFilter(Bid::PRODUCT_ID, $product->getId())
            ->addFieldToFilter(Bid::STATUS, Bid::BID_PENDING)
            ->addOrder(Bid::BID_ID);
    }


    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @param float $price
     * @return \Angel\Auction\Api\Data\BidInterface
     */
    public function createNewBid($customer, $price){
        /** @var Bid $bid */
        $bid = $this->bidFactory->create();
        $bid->setProductId($this->getProduct()->getId())
            ->setCustomerId($customer->getId())
            ->setPrice($price)
            ->setStatus(Bid::BID_PENDING);
        return $this->bidRepository->save($bid);
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @param float $price
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     */
    public function createNewAutoBid($customer, $price){
        /** @var AutoBid $bid */
        $autobid = $this->autoBidFactory->create();
        $autobid->setProductId($this->getProduct()->getId())
            ->setCustomerId($customer->getId())
            ->setPrice($price)
            ->setStatus();
        return $this->autoBidFactory->save($autobid);
    }

    public function checkAutoBid($customer, $price){

    }
}
