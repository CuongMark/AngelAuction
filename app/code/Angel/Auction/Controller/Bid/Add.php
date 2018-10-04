<?php


namespace Angel\Auction\Controller\Bid;

use Angel\Auction\Model\Product\Type as Auction;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Add extends \Angel\Auction\Controller\Bid\Bid
{
    /**
     * @var \Angel\Auction\Model\Auction
     */
    protected $auction;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        ProductRepositoryInterface $productRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Angel\Auction\Model\Auction $auction
    ){
        parent::__construct($context, $formKeyValidator, $jsonFactory, $productRepository, $customerSession);
        $this->auction = $auction;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute(){
        $result = $this->_resultJsonFactory->create();
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $result->setData([['error'=>__('Invalid form key!')]]);
        }

        $product = $this->_initProduct();
        if (!$product){
            return $result->setData([['error'=>__('Auction product does not exist!')]]);
        }
        /** @var \Angel\Auction\Model\Product\Type $productInstance */
        $productInstance = $product->getTypeInstance();
        if (!$productInstance->isProcessing($product)){
            return $result->setData([['error'=>__('Unable to bid this auctions!')]]);
        }

        $price = $this->getRequest()->getParam('price');
        if(!is_numeric($price)){
            return $result->setData(['error' => __('The price is not numberic.')]);
        }
        if(!$price || $price>=100000000||$price<=0){
            return $result->setData(['error' => __('The price is invalid.')]);
        }

        /*
         * check the price is larger Min next price
         */
        if($price < $product->getData(Auction::MIN_INTERVAL_FIELD)){
            return $result->setData(['error' => __('The bid need greater than %1.', $product->getData(Auction::MIN_INTERVAL_FIELD))]);
        }

        if($product->getData(Auction::MAX_INTERVAL_FIELD) && $price > $product->getData(Auction::MAX_INTERVAL_FIELD)){
            return $result->setData(['error' => __('The bid need less than %1.', $product->getData(Auction::MAX_INTERVAL_FIELD))]);
        }

        $this->auction->init($product);
        $customer = $this->customerSession->getCustomer();
        if (!$customer->getId()){
            return $result->setData(['error' => __('You are out of session. Please login again')]);
        }

        $lastestBid = $this->auction->getLastestBid();
        if ($lastestBid->getId() && $lastestBid->getCustomerId() == $customer->getId()){
            return $result->setData(['error' => __('You are the hightest bid!')]);
        }

        if ($this->getRequest()->getParam('isAutoBid')) {
            $this->auction->createNewAutoBid($customer, $price);
        } else {
            $this->auction->createNewBid($customer, $price);
        }

        return $result->setData(['success' => __('You placed a bid!')]);
    }
}
