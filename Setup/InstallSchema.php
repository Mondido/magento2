<?php

namespace Mondido\Mondido\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tables = [
            $installer->getTable('quote'),
            $installer->getTable('sales_order'),
            $installer->getTable('sales_order_grid')
        ];

        foreach($tables as $table) {
            $installer->getConnection()->addColumn(
                $table,
                'mondido_transaction',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'   => '64k',
                    'unsigned' => true,
                    'nullable' => true,
                    'comment'  => 'Mondido Transaction'
                ]
            );
        }

        $installer->endSetup();
    }
}
