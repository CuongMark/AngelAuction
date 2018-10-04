<?php


namespace Angel\Auction\Controller\Bid;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Controller\Product\View\ViewInterface;

abstract class Bid extends \Magento\Framework\App\Action\Action implements ViewInterface
{

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $_formKeyValidator;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        ProductRepositoryInterface $productRepository,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_formKeyValidator = $formKeyValidator;
        $this->_resultJsonFactory = $jsonFactory;
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Initialize product instance from request data
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    protected function _initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_objectManager->get(
                \Magento\Store\Model\StoreManagerInterface::class
            )->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    protected function getCustomerSession(){
        return $this->customerSession;
    }

    public function validateBid(){

    }
}
