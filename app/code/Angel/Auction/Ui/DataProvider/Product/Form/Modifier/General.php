<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Auction\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Ui\Component\Form;
use Magento\Framework\Stdlib\ArrayManager;

/**
 * Data provider for main panel of product page
 *
 * @api
 * @since 101.0.0
 */
class General extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    /**
     * @var LocatorInterface
     * @since 101.0.0
     */
    protected $locator;

    /**
     * @var ArrayManager
     * @since 101.0.0
     */
    protected $arrayManager;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    private $localeCurrency;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }


    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->customizeNewDateRangeField($meta);
        $meta = $this->customizeAuctionStatusField($meta);
        return $meta;
    }

    /**
     * Customize Status field
     *
     * @param array $meta
     * @return array
     * @since 101.0.0
     */
    protected function customizeAuctionStatusField(array $meta)
    {
        $fromField = 'auction_status';

        $auctionStatusFieldPath = $this->arrayManager->findPath($fromField, $meta, null, 'children');
        if ($auctionStatusFieldPath){
            $meta = $this->arrayManager->merge(
                $auctionStatusFieldPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'disabled' => false,
                ]
            );
        }
        return $meta;
    }


    /**
     * Customize "Set Product as New" date fields
     *
     * @param array $meta
     * @return array
     * @since 101.0.0
     */
    protected function customizeNewDateRangeField(array $meta)
    {
        $fromField = 'auction_start_time';
        $toField = 'auction_end_time';

        $fromFieldPath = $this->arrayManager->findPath($fromField, $meta, null, 'children');
        $toFieldPath = $this->arrayManager->findPath($toField, $meta, null, 'children');

        if ($fromFieldPath && $toFieldPath) {
            $fromContainerPath = $this->arrayManager->slicePath($fromFieldPath, 0, -2);
            $toContainerPath = $this->arrayManager->slicePath($toFieldPath, 0, -2);

            $meta = $this->arrayManager->merge(
                $fromFieldPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'label' => __('Auction Process From'),
                    'additionalClasses' => 'admin__field-date',
                    'options' => [
                        'dateFormat' => 'Y-m-d',
                        'timeFormat' => 'HH:mm:ss',
                        'showsTime' => true
                    ]
                ]
            );
            $meta = $this->arrayManager->merge(
                $toFieldPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'label' => __('To'),
                    'scopeLabel' => null,
                    'additionalClasses' => 'admin__field-date',
                    'options' => [
                        'dateFormat' => 'Y-m-d',
                        'timeFormat' => 'HH:mm:ss',
                        'showsTime' => true
                    ]
                ]
            );
            $meta = $this->arrayManager->merge(
                $fromContainerPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'label' => __('Auction Process From'),
                    'additionalClasses' => 'admin__control-grouped-date',
                    'breakLine' => false,
                    'component' => 'Magento_Ui/js/form/components/group',
                ]
            );
            $meta = $this->arrayManager->set(
                $fromContainerPath . '/children/' . $toField,
                $meta,
                $this->arrayManager->get($toFieldPath, $meta)
            );

            $meta = $this->arrayManager->remove($toContainerPath, $meta);
        }

        return $meta;
    }
}
