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
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        array $data = []
    ){
        parent::__construct($context, $arrayUtils, $data);
        $this->priceCurrency = $priceCurrency;
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

    public function getTimeLeft(){
        return $this->getProduct()->getTypeInstance()->getTimeLeft($this->getProduct());
    }

    public function getCurrentBidPriceFormated(){
        return $this->priceCurrency->format($this->getProduct()->getData('auction_init_price'));
    }
}
