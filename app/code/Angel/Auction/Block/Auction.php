<?php


namespace Angel\Auction\Block;

use Magento\Framework\Pricing\PriceCurrencyInterface;
class Auction extends \Magento\Framework\View\Element\Template
{

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Angel\Auction\Model\ResourceModel\Bid\CollectionFactory
     */
    protected $bidCollectionFactory;

    /**
     * @var \Angel\Auction\Model\ResourceModel\AutoBid\CollectionFactory
     */
    protected $autoBidCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        PriceCurrencyInterface $priceCurrency,
        \Angel\Auction\Model\ResourceModel\Bid\CollectionFactory $bidCollectionFactory,
        \Angel\Auction\Model\ResourceModel\AutoBid\CollectionFactory $autoBidCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->bidCollectionFactory = $bidCollectionFactory;
        $this->autoBidCollectionFactory = $autoBidCollectionFactory;
        $this->priceCurrency = $priceCurrency;
        $this->customerSession = $customerSession;
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

    /**
     * @return \Angel\Auction\Model\ResourceModel\Bid\Collection|array
     */
    public function getBids(){
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId){
            return [];
        }
        /** @var \Angel\Auction\Model\ResourceModel\Bid\Collection $collection */
        $collection = $this->bidCollectionFactory->create();
        $collection->addFieldToFilter(\Angel\Auction\Model\Bid::CUSTOMER_ID, $customerId)
            ->addOrder(\Angel\Auction\Model\Bid::PRICE);
        $productNameAttributeId = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Eav\Model\Config')
            ->getAttribute(\Magento\Catalog\Model\Product::ENTITY, \Magento\Catalog\Api\Data\ProductInterface::NAME)
            ->getAttributeId();
        $collection->getSelect()->joinLeft(['product_varchar' => $collection->getTable('catalog_product_entity_varchar')],
            "product_varchar.entity_id = main_table.product_id AND product_varchar.attribute_id = $productNameAttributeId", []
        )->columns(['name' => 'product_varchar.value']);
        return $collection;
    }

    /**
     * @return \Angel\Auction\Model\ResourceModel\Bid\Collection|array
     */
    public function getAutoBids(){
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId){
            return [];
        }
        /** @var \Angel\Auction\Model\ResourceModel\Bid\Collection $collection */
        $collection = $this->autoBidCollectionFactory->create();
        $collection->addFieldToFilter(\Angel\Auction\Model\Bid::CUSTOMER_ID, $customerId)
            ->addOrder(\Angel\Auction\Model\Bid::PRICE);
        $productNameAttributeId = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Eav\Model\Config')
            ->getAttribute(\Magento\Catalog\Model\Product::ENTITY, \Magento\Catalog\Api\Data\ProductInterface::NAME)
            ->getAttributeId();
        $collection->getSelect()->joinLeft(['product_varchar' => $collection->getTable('catalog_product_entity_varchar')],
            "product_varchar.entity_id = main_table.product_id AND product_varchar.attribute_id = $productNameAttributeId", []
        )->columns(['name' => 'product_varchar.value']);
        return $collection;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getBids()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.pager'
            )->setCollection(
                $this->getBids()
            );
            $this->setChild('pager', $pager);
            $this->getBids()->load();
        }
        return $this;
    }
}
