<?php

namespace Made\PaidModifier\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $connection = $setup->getConnection();

        $table = $connection->newTable($setup->getTable('made_paid_modifier'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true,
                'primary'  => true,
                'identity' => true
            ], 'Entity ID')
            ->addColumn('order_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true
            ], 'Order ID')
            ->addColumn('modifier', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
                'nullable' => false
            ], 'Modifier')
            ->addColumn('weighted_base_total_paid', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
                'nullable' => false
            ], '= modifier * sales_order.base_total_paid')
            ->addColumn('weighted_total_paid', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
                'nullable' => false
            ], '= modifier * sales_order.total_paid')
            ->addIndex($setup->getIdxName('made_paid_modifier', ['order_id']), ['order_id'])
            ->addForeignKey(
                $setup->getFkName('made_paid_modifier', 'order_id', 'sales_order', 'entity_id'),
                'order_id',
                $setup->getTable('sales_order'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Made Paid Modifier');

        $connection->createTable($table);
    }
}
