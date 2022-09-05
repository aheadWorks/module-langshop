<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Setup;

use Aheadworks\Langshop\Model\ResourceModel\Status;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    /**
     * Invoked when remove-data flag is set during module uninstall.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $connection = $setup->getConnection();
        $connection->dropTable(
            $setup->getTable(Status::TABLE_NAME)
        );
    }
}
