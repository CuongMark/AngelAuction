<?php


namespace Angel\Auction\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Angel\Auction\Model\Product\Type;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $fieldList = [
//            'price',
            'cost',
            'weight'
        ];
        foreach ($fieldList as $field) {
            $applyTo = explode(
                ',',
                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
            );
            if (!in_array(Type::TYPE_CODE, $applyTo)) {
                $applyTo[] = Type::TYPE_CODE;
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $field,
                    'apply_to',
                    implode(',', $applyTo)
                );
            }
        }

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_start_time',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Start Time',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_end_time',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'End Time',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_init_price',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Init Price',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_min_interval',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Min Interval',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_max_interval',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Max interval',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_extended_time',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Extended Time',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_allow_buynow',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Allow Buy Now',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'auction_status',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Auction Status',
                'input' => 'select',
                'class' => '',
                'source' => \Angel\Auction\Model\Product\Attribute\Source\Status::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => Type::TYPE_CODE,
                'system' => 1,
                'group' => 'General',
                'option' => []
            ]
        );
    }

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
}
