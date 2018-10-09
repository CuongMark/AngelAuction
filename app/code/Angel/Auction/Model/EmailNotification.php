<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Auction\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EmailNotification implements EmailNotificationInterface{

    const XML_PATH_NEW_BID = 'auction/email/new_bid';
    const XML_PATH_AUCTION_WINNING = 'auction/email/winning';
    const XML_PATH_AUCTION_FINISHED = 'auction/email/auction_finished';
    const XML_PATH_AUCTION_CANCELED = 'auction/email/auction_canceled';
    const XML_PATH_OVER_BID = 'auction/email/over_bid';
    const XML_PATH_SENDER_EMAIL = 'trans_email/ident_general';

    /**#@-*/

    /**#@-*/
    private $customerRegistry;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var CustomerViewHelper
     */
    protected $customerViewHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataProcessor;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Customer\Model\ResourceModel\CustomerRepository
     */
    protected $customerRespository;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * stdlib timezone.
     *
     * @var \Magento\Framework\Stdlib\DateTime\Timezone
     */
    protected $_stdTimezone;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * EmailNotification constructor.
     * @param CustomerRegistry $customerRegistry
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param CustomerViewHelper $customerViewHelper
     * @param DataObjectProcessor $dataProcessor
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Model\ResourceModel\CustomerRepository $customerRepository
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Framework\Stdlib\DateTime\Timezone $timezone
     */
    public function __construct(
        CustomerRegistry $customerRegistry,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        CustomerViewHelper $customerViewHelper,
        DataObjectProcessor $dataProcessor,
        ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\ResourceModel\CustomerRepository $customerRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone
    ) {
        $this->customerRegistry = $customerRegistry;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->customerViewHelper = $customerViewHelper;
        $this->dataProcessor = $dataProcessor;
        $this->scopeConfig = $scopeConfig;
        $this->customerRespository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->_stdTimezone = $timezone;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Send corresponding email template
     *
     * @param CustomerInterface $customer
     * @param string $template configuration path of email template
     * @param string $sender configuration path of email identity
     * @param array $templateParams
     * @param int|null $storeId
     * @param string $email
     * @return void
     */
    private function sendEmailTemplate(
        $customer,
        $template,
        $sender,
        $templateParams = [],
        $storeId = null,
        $email = null
    ) {
        $templateId = $this->scopeConfig->getValue($template, 'store', $storeId);
        if ($email === null) {
            $email = $customer->getEmail();
        }
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($templateParams)
            ->setFrom($this->scopeConfig->getValue($sender, 'store', $storeId))
            ->addTo($email, $this->customerViewHelper->getCustomerName($customer))
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * @param \Angel\Auction\Model\Bid $bid
     */
    public function sentNewBidNotificatuon($bid){
        $customer = $this->customerRespository->getById($bid->getCustomerId());
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_NEW_BID,
            self::XML_PATH_SENDER_EMAIL,
            [
                'customer' => $customer,
                'store' => $this->storeManager->getStore($storeId),
                'auction_name' => $this->productRepository->getById($bid->getProductId())->getName(),
                'customer_name' => $this->customerViewHelper->getCustomerName($customer),
                'bid_price' => $this->priceCurrency->format($bid->getPrice()),
                'created_at' => $this->_stdTimezone->formatDateTime($bid->getCreatedTime()),
                'time_left' => ''
            ],
            $storeId,
            $customer->getEmail()
        );
    }

    /**
     * @param \Angel\Auction\Model\Bid $bid
     */
    public function sentAuctionWinningNotification($bid){
        $customer = $this->customerRespository->getById($bid->getCustomerId());
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_NEW_BID,
            self::XML_PATH_SENDER_EMAIL,
            [
                'customer' => $customer,
                'store' => $this->storeManager->getStore($storeId),
                'auction_name' => $this->productRepository->getById($bid->getProductId())->getName(),
                'customer_name' => $this->customerViewHelper->getCustomerName($customer),
                'bid_price' => $bid->getPrice(),
                'created_at' => $bid->getCreatedTime(),
                'time_left' => ''
            ],
            $storeId,
            $customer->getEmail()
        );
    }

    /**
     * @param \Angel\Auction\Model\Bid $lastBid
     * @param \Angel\Auction\Model\Bid $bid
     */
    public function sentOverBidNotifycation($lastBid, $bid){
        $customer = $this->customerRespository->getById($lastBid->getCustomerId());
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_NEW_BID,
            self::XML_PATH_SENDER_EMAIL,
            [
                'customer' => $customer,
                'store' => $this->storeManager->getStore($storeId),
                'auction_name' => $this->productRepository->getById($bid->getProductId())->getName(),
                'customer_name' => $this->customerViewHelper->getCustomerName($customer),
                'bid_price' => $this->priceCurrency->format($bid->getPrice()),
                'created_at' => $this->_stdTimezone->formatDateTime($bid->getCreatedTime()),
                'time_left' => ''
            ],
            $storeId,
            $customer->getEmail()
        );
    }

    public function sentAuctionFinishedNotification($product, $email){

    }

    public function sentAuctionCaceledNotification($product){

    }


    /**
     * Create an object with data merged from Customer and CustomerSecure
     *
     * @param CustomerInterface $customer
     * @return \Magento\Customer\Model\Data\CustomerSecure
     */
    private function getFullCustomerObject($customer)
    {
        // No need to flatten the custom attributes or nested objects since the only usage is for email templates and
        // object passed for events
        $mergedCustomerData = $this->customerRegistry->retrieveSecureData($customer->getId());
        $customerData = $this->dataProcessor
            ->buildOutputDataArray($customer, \Magento\Customer\Api\Data\CustomerInterface::class);
        $mergedCustomerData->addData($customerData);
        $mergedCustomerData->setData('name', $this->customerViewHelper->getCustomerName($customer));
        return $mergedCustomerData;
    }

    /**
     * Get either first store ID from a set website or the provided as default
     *
     * @param CustomerInterface $customer
     * @param int|string|null $defaultStoreId
     * @return int
     */
    private function getWebsiteStoreId($customer, $defaultStoreId = null)
    {
        if ($customer->getWebsiteId() != 0 && empty($defaultStoreId)) {
            $storeIds = $this->storeManager->getWebsite($customer->getWebsiteId())->getStoreIds();
            $defaultStoreId = reset($storeIds);
        }
        return $defaultStoreId;
    }
}
