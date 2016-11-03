<?php
/**
 * Mondido
 *
 * PHP version 5.6
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */

namespace Mondido\Mondido\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Install schema
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install
     *
     * @param Magento\Framework\Setup\SchemaSetupInterface   $setup   Schema setup interface
     * @param Magento\Framework\Setup\ModuleContextInterface $context Module context interface
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tables = [
            $installer->getTable('quote'),
            $installer->getTable('sales_order'),
            $installer->getTable('sales_order_grid')
        ];

        foreach ($tables as $table) {
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
