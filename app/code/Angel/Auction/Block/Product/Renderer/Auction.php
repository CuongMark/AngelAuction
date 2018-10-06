<?php


namespace Angel\Auction\Block\Product\Renderer;

use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Auction extends \Magento\Catalog\Block\Product\View\AbstractView
{

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Angel\Auction\Model\Auction
     */
    protected $auction;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Angel\Auction\Model\Auction $auction,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ){
        parent::__construct($context, $arrayUtils, $data);
        $this->priceCurrency = $priceCurrency;
        $this->_request = $context->getRequest();
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->auction = $auction;
        $this->customerSession = $customerSession;
    }

    /**
     * Set product to block
     *
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Override parent function
     *
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->product) {
            $this->product = parent::getProduct();
        }
        return $this->product;
    }

    public function getAuction(){
        if(!$this->auction->getProduct()){
            $this->auction->init($this->getProduct());
        }
        return $this->auction;
    }

    public function isHightest(){
        return $this->getAuction()->isHightest();
    }

    public function isFinished(){
        return $this->getAuction()->isFinished();
    }

    public function isProcessing(){
        return $this->getAuction()->isProcessing();
    }

    public function isNotStart(){
        return $this->getAuction()->isNotStart();
    }

    public function getTimeLeft(){
        return $this->getProduct()->getTypeInstance()->getTimeLeft($this->getProduct());
    }

    public function getCurrentBidPriceFormated(){
        return $this->priceCurrency->format($this->getCurrentBidPrice());
    }

    public function getCurrentBidPrice(){
        return $this->getAuction()->getCurrentBidPrice();
    }

    public function getNextMinBidPrice(){
        return $this->getAuction()->getNextMinBidPrice();
    }

    public function getNextMaxBidPrice(){
        return $this->getAuction()->getNextMaxBidPrice();
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getSuggess(){
        if ($this->getNextMaxBidPrice())
            return __('Your bid must be equal or greater than %1 and less or equal than %2',$this->priceCurrency->format($this->getNextMinBidPrice()), $this->priceCurrency->format($this->getNextMaxBidPrice()));
        else
            return __('Your bid must be equal or greater than %1', $this->priceCurrency->format($this->getNextMinBidPrice()));
    }

    public function getQuantityValidators(){
        $validators = [];
        $validators['required-number'] = true;
        $validators['validate-item-quantity'] = [
            'minAllowed' => $this->getNextMinBidPrice()
        ];
        if ($this->getNextMaxBidPrice()){
            $validators['validate-item-quantity']['maxAllowed'] = $this->getNextMaxBidPrice();
        }
        return $validators;
    }

    public function getStartTimeFormated(){
        return $this->formatDate($this->getProduct()->getData('auction_start_time'), 2, true);
    }

    public function getEndTimeFormated(){
        return $this->formatDate($this->getProduct()->getData('auction_end_time'), 2, true);
    }

    public function isBidAble(){
        return $this->customerSession->isLoggedIn() && $this->isProcessing();
    }

    public function isLoggedIn(){

    }

    public function isStarted(){
        return true;
    }

    public function getBidUrl(){
        $routeParams = [
            'product' => $this->getProduct()->getEntityId(),
            '_secure' => $this->_request->isSecure()
        ];
        return $this->_urlBuilder->getUrl('auction/bid/add', $routeParams);
    }

}
