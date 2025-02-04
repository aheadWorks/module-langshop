<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\ResourceModel\Entity;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;

class Binding extends AbstractDb
{
    public const MAIN_TABLE_NAME = 'aw_ls_resource_binding';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE_NAME, ResourceBindingInterface::ID);
    }

    /**
     * Get ticket ID by external link
     *
     * @param string $resourceType
     * @param int $translatedResourceId
     * @return int|null
     * @throws LocalizedException
     */
    public function getOriginalResourceId(string $resourceType, int $translatedResourceId): ?int
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ResourceBindingInterface::ORIGINAL_RESOURCE_ID)
            ->where('resource_type = ?', $resourceType)
            ->where('translated_resource_id = ?', $translatedResourceId);

        $result = $connection->fetchOne($select);
        return $result ? (int)$result : null;
    }

    /**
     * Mass save
     *
     * @param array $data
     * @return $this
     * @throws LocalizedException
     */
    public function massSave(array $data): Binding
    {
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            $data
        );

        return $this;
    }

    /**
     * Check if resource is already bound
     *
     * @param string $resourceType
     * @param int $translatedId
     * @return bool
     * @throws LocalizedException
     */
    public function checkIsAlreadyBound(string $resourceType, int $translatedId): bool
    {
        $select = $this->getConnection()->select()
            ->from(['bt' => $this->getMainTable()], [])
            ->where('resource_type = ?', $resourceType)
            ->where('translated_resource_id = ?', $translatedId)
            ->limit(1)
            ->columns([new \Zend_Db_Expr(1)]);

        return (bool)$this->getConnection()->fetchOne($select);
    }

    /**
     * Remove binding
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return void
     * @throws LocalizedException
     */
    public function removeBinding(string $resourceType, int $resourceId): void
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            [
                'resource_type = ?' => $resourceType,
                'translated_resource_id  = ?' => $resourceId
            ]
        );
    }

    /**
     * Get resource store ID
     *
     * @param string $resourceType
     * @param int $translatedResourceId
     * @return int|null
     * @throws LocalizedException
     */
    public function getResourceStoreId(string $resourceType, int $translatedResourceId): ?int
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ResourceBindingInterface::STORE_ID)
            ->where('resource_type = ?', $resourceType)
            ->where('translated_resource_id = ?', $translatedResourceId);

        $result = $connection->fetchOne($select);
        return $result !== null ? (int)$result : null;
    }
}
