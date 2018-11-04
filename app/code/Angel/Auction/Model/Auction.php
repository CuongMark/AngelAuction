<?php


namespace Angel\Auction\Model;

use Angel\Auction\Model\Product\Attribute\Source\Status;
use Angel\Auction\Model\Product\Type;

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
     * @var AutoBidFactory
     */
    protected $autoBidFactory;

    /**
     * @var AutoBidRepository
     */
    protected $autoBidRepository;

    /**
     * @var ResourceModel\AutoBid\CollectionFactory
     */
    protected $autoBidCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var EmailNotificationFactory
     */
    protected $emailNotificationFactory;

    public function __construct(
        \Angel\Auction\Model\ResourceModel\Bid\CollectionFactory $bidCollectionFactory,
        \Angel\Auction\Model\BidFactory $bidFactory,
        \Angel\Auction\Model\BidRepository $bidRepository,
        \Angel\Auction\Model\ResourceModel\AutoBid\CollectionFactory $autoBidCollectionFactory,
        \Angel\Auction\Model\AutoBidFactory $autoBidFactory,
        \Angel\Auction\Model\AutoBidRepository $autoBidRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Angel\Auction\Model\EmailNotificationFactory $emailNotificationFactory
    ){
        $this->bidCollectionFactory = $bidCollectionFactory;
        $this->bidFactory = $bidFactory;
        $this->bidRepository = $bidRepository;
        $this->autoBidCollectionFactory = $autoBidCollectionFactory;
        $this->autoBidFactory = $autoBidFactory;
        $this->autoBidRepository = $autoBidRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
        $this->emailNotificationFactory = $emailNotificationFactory;
    }

    public function init($product){
        $this->product = $product;
        return $this;
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
     * @return bool
     */
    public function isFinished(){
        return $this->product->getData(self::STATUS_FIELD) == \Angel\Auction\Model\Product\Attribute\Source\Status::FINISHED;
    }

    /**
     * @return bool
     */
    public function isNotStart(){
        return $this->product->getData(self::STATUS_FIELD) == \Angel\Auction\Model\Product\Attribute\Source\Status::NOT_START;
    }

    /**
     * @return mixed
     */
    public function getStatusLable(){
        $auctionStatusOptions = Status::options();
        return $auctionStatusOptions[$this->getProduct()->getData(self::STATUS_FIELD)]['label'];
    }

    /**
     * @return bool
     */
    public function isWinner($customerId = null){
        $winningBid = $this->getWinningBid();
        if (!$customerId){
            $customerId = $this->customerSession->getCustomerId();
        }
        return $winningBid->getId() && $winningBid->getCustomerId() == $customerId;
    }

    /**
     * @return bool
     */
    public function isHightest($customerId = null){
        $lastBid = $this->getLastestBid();
        if (!$customerId){
            $customerId = $this->customerSession->getCustomerId();
        }
        return $lastBid->getId() && $lastBid->getCustomerId() == $customerId;
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
        return $this->getBids()->getFirstItem();
    }

    /**
     * @return \Angel\Auction\Model\ResourceModel\Bid\Collection
     */
    public function getBids(){
        /** @var \Angel\Auction\Model\ResourceModel\Bid\Collection $bidCollection */
        $bidCollection = $this->bidCollectionFactory->create();
        return $bidCollection->addFieldToFilter(Bid::PRODUCT_ID, $this->getProduct()->getId())
            ->addFieldToFilter(Bid::STATUS, ['neq' => Bid::BID_CANCELED])
            ->addOrder(Bid::BID_ID);
    }

    /**
     * @return \Angel\Auction\Model\Bid|\Magento\Framework\DataObject
     */
    public function getWinningBid(){
        /** @var \Angel\Auction\Model\ResourceModel\Bid\Collection $bidCollection */
        $bidCollection = $this->bidCollectionFactory->create();
        return $bidCollection->addFieldToFilter(Bid::PRODUCT_ID, $this->getProduct()->getId())
            ->addFieldToFilter(Bid::STATUS, Bid::BID_WON)
            ->addOrder(Bid::BID_ID)
            ->setCurPage(1)
            ->setPageSize(1)
            ->getFirstItem();
    }

    /**
     * @return \Angel\Auction\Model\ResourceModel\AutoBid\Collection
     */
    public function getAutoBids(){
        /** @var \Angel\Auction\Model\ResourceModel\AutoBid\Collection $autoBidCollection */
        $autoBidCollection = $this->autoBidCollectionFactory->create();
        return $autoBidCollection->addFieldToFilter(AutoBid::PRODUCT_ID, $this->getProduct()->getId())
            ->addFieldToFilter(AutoBid::STATUS, AutoBid::BID_PENDING)
            ->addOrder(AutoBid::PRICE);
    }

    /**
     * @return \Angel\Auction\Model\ResourceModel\AutoBid\Collection
     */
    public function getAvaiableAutoBid(){
        return $this->getAutoBids()->addFieldToFilter(AutoBid::PRICE, ['gt' => $this->getCurrentBidPrice()]);
    }


    /**
     * @param $customerId
     * @param $price
     * @return \Angel\Auction\Api\Data\BidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createNewBid($customerId, $price){
        $lastBid = $this->getLastestBid();
        /** @var Bid $bid */
        $bid = $this->bidFactory->create();
        $bid->setProductId($this->getProduct()->getId())
            ->setCustomerId($customerId)
            ->setPrice($price)
            ->setStatus(Bid::BID_PENDING);
        $this->emailNotificationFactory->create()->sentNewBidNotificatuon($bid);
        if ($lastBid->getId()){
            $this->emailNotificationFactory->create()->sentOverBidNotifycation($lastBid, $bid);
        }
        return $this->bidRepository->save($bid);
    }

    /**
     * @param int $customerId
     * @param float $price
     * @return \Angel\Auction\Api\Data\AutoBidInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createNewAutoBid($customerId, $price){
        /** @var AutoBid $autobid */
        $autobid = $this->autoBidFactory->create();
        $autobid->setProductId($this->getProduct()->getId())
            ->setCustomerId($customerId)
            ->setPrice($price)
            ->setStatus(AutoBid::BID_PENDING);
        return $this->autoBidRepository->save($autobid);
    }

    /**
     * @return \Angel\Auction\Api\Data\BidInterface|bool
     */
    public function checkAutoBid(){
        $lastBid = $this->getLastestBid();
        $autoBids = $this->getAvaiableAutoBid();
        if (!$autoBids->getSize()){
            return false;
        } else if ($autoBids->getSize() == 1){
            /** @var AutoBid $lastAutobid */
            $lastAutobid = $autoBids->getFirstItem();
            if (!$lastBid->getId() || $lastBid->getCustomerId() != $lastAutobid->getCustomerId()){
                $price = min($this->getNextMinBidPrice(), $lastAutobid->getPrice());
                return $this->createNewBid($lastAutobid->getCustomerId(), $price);
            } else {
                return false;
            }
        } else if ($autoBids->getSize() == 2){
            /** @var AutoBid $autoBidBiger */
            $autoBidBiger = $autoBids->getFirstItem();
            /** @var AutoBid $autoBidSmaller */
            $autoBidSmaller = $autoBids->getLastItem();
            if ($lastBid->getCustomerId()==$autoBidBiger->getCustomerId() && $lastBid->getCustomerId()==$autoBidSmaller->getCustomerId()){
                return false;
            } else if ($autoBidBiger->getPrice() == $autoBidSmaller->getPrice()){
                if ($autoBidBiger->getId() > $autoBidSmaller->getPrice()){
                    return $this->createNewBid($autoBidSmaller->getCustomerId(), $autoBidSmaller->getPrice());
                } else {
                    return $this->createNewBid($autoBidBiger->getCustomerId(), $autoBidBiger->getPrice());
                }
            }
            $this->createNewBid($autoBidSmaller->getCustomerId(), $autoBidSmaller->getPrice());
            $price = min( (float)$autoBidSmaller->getPrice() + (float)$this->getProduct()->getData(self::MIN_INTERVAL_FIELD), $autoBidBiger->getPrice());
            return $this->createNewBid($autoBidBiger->getCustomerId(), $price);
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getStartTime(){
        return $this->getProduct()->getData(self::START_TIME_FIELD);
    }

    /**
     * @return mixed
     */
    public function getEndTime(){
        return $this->getProduct()->getData(self::END_TIME_FIELD);
    }

    /**
     * @param null $product
     * @return \Magento\Catalog\Model\Product|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function updateStatus($product = null){
        if (!$product) {
            $product = $this->getProduct();
        }
        if (!$product || $product->getTypeId() != Product\Type::TYPE_CODE ||
            $product->getData(self::STATUS_FIELD)==null || $product->getData(self::START_TIME_FIELD) == null  || $product->getData(self::END_TIME_FIELD) == null ||
            !in_array($product->getData(self::STATUS_FIELD),[Product\Attribute\Source\Status::NOT_START, Product\Attribute\Source\Status::PROCESSING])){
            return $product;
        }
        $now = new \DateTime();
        $start = new \DateTime($product->getData(self::START_TIME_FIELD));
        $end = new \DateTime($product->getData(self::END_TIME_FIELD));
        switch ($product->getData(self::STATUS_FIELD)){
            case Product\Attribute\Source\Status::NOT_START:
                if ($now->getTimestamp() > $start->getTimestamp() && $now->getTimestamp() < $end->getTimestamp()){
                    $product->setData(self::STATUS_FIELD, Product\Attribute\Source\Status::PROCESSING);
                    return $this->productRepository->save($product);
                } else if ($now->getTimestamp() >= $end->getTimestamp()){
                    $product->setData(self::STATUS_FIELD, Product\Attribute\Source\Status::FINISHED);
                    return $this->productRepository->save($product);
                }
                return $product;
            case Product\Attribute\Source\Status::PROCESSING:
                if ($now->getTimestamp() >= $end->getTimestamp()){
                    $product->setData(self::STATUS_FIELD, Product\Attribute\Source\Status::FINISHED);
                    $this->productRepository->save($product);
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $CategoryLinkRepository = $objectManager->get('\Magento\Catalog\Api\CategoryLinkManagementInterface');
                    $CategoryLinkRepository->assignProductToCategories($product->getSku(), []);

                    $bid = $this->getLastestBid();
                    if ($bid->getId()) {
                        $bid->setStatus(Bid::BID_WON);
                        $this->bidRepository->save($bid);
                        $this->emailNotificationFactory->create()->sentAuctionWinningNotification($bid);
                    }


                    $bids = $this->getBids();
                    /** @var Bid $_bid */
                    foreach ($bids as $_bid){
                        if ($_bid->getId() != $bid->getId()){
                            $_bid->setStatus(Bid::BID_LOSE);
                            $this->bidRepository->save($_bid);
                        }
                    }
                }
                return $product;
            case Product\Attribute\Source\Status::FINISHED:
                return $product;
            case Product\Attribute\Source\Status::CANCEL:
                return $product;
            case Product\Attribute\Source\Status::FALSE:
                return $product;
            default:
                return $product;
        }
    }

    public function massUpdateStatus(){
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $auctionProductCollection */
        $auctionProductCollection = $this->productCollectionFactory->create()->addAttributeToFilter('type_id', Product\Type::TYPE_CODE)
            ->addAttributeToFilter(self::STATUS_FIELD, ['in' => [Product\Attribute\Source\Status::NOT_START, Product\Attribute\Source\Status::PROCESSING]]);
        foreach ($auctionProductCollection as $_auctionProduct){
            $this->updateStatus($_auctionProduct);
        }
    }
}
