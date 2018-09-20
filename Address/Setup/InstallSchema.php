<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */
namespace Sslwireless\Address\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('directory_region_city')) {
            $tableCity = $installer->getConnection()
                ->newTable($installer->getTable('directory_region_city'))
                ->addColumn(
                    'city_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'City Id'
                )
                ->addColumn(
                    'region_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false,],
                    'Region Id'
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['default' => null],
                    'Code'
                )
                ->addColumn(
                    'default_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['default' => null],
                    'City Name'
                )
                ->addForeignKey(
                    $setup->getFkName('directory_region_city', 'region_id', 'directory_country_region', 'region_id'),
                    'region_id',
                    $setup->getTable('directory_country_region'),
                    'region_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            $installer->getConnection()->createTable($tableCity);
        }
        if (!$installer->tableExists('directory_region_city_name')) {
            $tableCityName = $installer->getConnection()
                ->newTable($installer->getTable('directory_region_city_name'))
                ->addColumn(
                    'locale',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    8,
                    ['default' => null],
                    'Locale'
                )
                ->addColumn(
                    'city_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false,],
                    'City Id'
                )
                ->addColumn(
                    'name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['default' => null],
                    'City Name'
                )
                ->addForeignKey(
                    $setup->getFkName('directory_region_city_name', 'city_id', 'directory_region_city', 'city_id'),
                    'city_id',
                    $setup->getTable('directory_region_city'),
                    'city_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            $installer->getConnection()->createTable($tableCityName);
        }

        if (!$installer->tableExists('directory_city_township')) {
            $tableTownship = $installer->getConnection()
                ->newTable($installer->getTable('directory_city_township'))
                ->addColumn(
                    'township_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Township Id'
                )
                ->addColumn(
                    'city_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false,],
                    'City Id'
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['default' => null],
                    'Code'
                )
                ->addColumn(
                    'default_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['default' => null],
                    'Township Name'
                )
                ->addColumn(
                    'postcode',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    ['default' => null],
                    'Postcode'
                )
                ->addForeignKey(
                    $setup->getFkName('directory_city_township', 'city_id', 'directory_region_city', 'city_id'),
                    'city_id',
                    $setup->getTable('directory_region_city'),
                    'city_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            $installer->getConnection()->createTable($tableTownship);
        }
        if (!$installer->tableExists('directory_city_township_name')) {
            $tableTownshipName = $installer->getConnection()
                ->newTable($installer->getTable('directory_city_township_name'))
                ->addColumn(
                    'locale',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    8,
                    ['default' => null],
                    'Locale'
                )
                ->addColumn(
                    'township_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false,],
                    'Township Id'
                )
                ->addColumn(
                    'name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['default' => null],
                    'Township Name'
                )
                ->addForeignKey(
                    $setup->getFkName('directory_city_township_name', 'township_id', 'directory_city_township', 'township_id'),
                    'township_id',
                    $setup->getTable('directory_city_township'),
                    'township_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            $installer->getConnection()->createTable($tableTownshipName);
        }

        $customer_address = $setup->getTable('customer_address_entity');
        $quote_address = $setup->getTable('quote_address');
        $order_address = $setup->getTable('sales_order_address');
        $columns = [
            'city_id' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => true,
                'comment' => 'City'
            ],
            'township' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Township'
            ],
            'township_id' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => true,
                'comment' => 'Township'
            ]
        ];
        if ($setup->getConnection()->isTableExists($customer_address) == true) {
            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($customer_address, $name, $definition);
            }
        }
        if ($setup->getConnection()->isTableExists($quote_address) == true) {
            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($quote_address, $name, $definition);
            }
        }
        if ($setup->getConnection()->isTableExists($order_address) == true) {
            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($order_address, $name, $definition);
            }
        }
        $installer->endSetup();
    }
}
