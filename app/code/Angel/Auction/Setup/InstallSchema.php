<?php


namespace Angel\Auction\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\DB\Ddl\Table;
use Angel\Auction\Model\ResourceModel\Bid;
use Angel\Auction\Model\ResourceModel\AutoBid;
use Angel\Auction\Model\ResourceModel\WatchList;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $table_angel_auction_bid = $setup->getConnection()->newTable($setup->getTable(Bid::TABLE_NAME));

        $table_angel_auction_bid->addColumn(
            'bid_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_auction_bid->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Entity Id'
        );

        $table_angel_auction_bid->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => false, 'unsigned' => true, 'nullable' => false, 'primary' => false],
            'Customer Id'
        );

        $table_angel_auction_bid->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Order Id'
        );

        $table_angel_auction_bid->addColumn(
            'autobid_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'AutoBid Id'
        );

        $table_angel_auction_bid->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'Bid Price'
        );

        $table_angel_auction_bid->addColumn(
            'created_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Create Time'
        );

        $table_angel_auction_bid->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Status'
        );

        $table_angel_auction_bid->addForeignKey(
            $setup->getFkName(Bid::TABLE_NAME, 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_auction_bid->addForeignKey(
            $setup->getFkName(Bid::TABLE_NAME, 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $setup->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_auction_autobid = $setup->getConnection()->newTable($setup->getTable(AutoBid::TABLE_NAME));

        $table_angel_auction_autobid->addColumn(
            'autobid_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_auction_autobid->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'product_id'
        );

        $table_angel_auction_autobid->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => false, 'unsigned' => true, 'nullable' => false, 'primary' => false],
            'Customer Id'
        );

        $table_angel_auction_autobid->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'price'
        );

        $table_angel_auction_autobid->addColumn(
            'created_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'created_time'
        );

        $table_angel_auction_autobid->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'status'
        );

        $table_angel_auction_autobid->addForeignKey(
            $setup->getFkName(AutoBid::TABLE_NAME, 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_auction_autobid->addForeignKey(
            $setup->getFkName(AutoBid::TABLE_NAME, 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $setup->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_auction_watchlist = $setup->getConnection()->newTable($setup->getTable(WatchList::TABLE_NAME));

        $table_angel_auction_watchlist->addColumn(
            'watchlist_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_auction_watchlist->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        );

        $table_angel_auction_watchlist->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => false, 'unsigned' => true, 'nullable' => false, 'primary' => false],
            'Customer Id'
        );

        $table_angel_auction_watchlist->addForeignKey(
            $setup->getFkName(WatchList::TABLE_NAME, 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_auction_watchlist->addForeignKey(
            $setup->getFkName(WatchList::TABLE_NAME, 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $setup->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        //Your install script

        $setup->getConnection()->createTable($table_angel_auction_watchlist);

        $setup->getConnection()->createTable($table_angel_auction_autobid);

        $setup->getConnection()->createTable($table_angel_auction_bid);
    }
}
