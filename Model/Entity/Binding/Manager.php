<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Binding;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;
use Aheadworks\Langshop\Api\Data\ResourceBindingInterface;
use Aheadworks\Langshop\Model\ResourceModel\Entity\Binding as BindingResource;

class Manager
{
    public const BINDING_DATA = 'aw_ls_binding_data';

    public const BINDING_MODE = 'aw_ls_binding';
    public const BINDING_SKIP = 'skip';
    public const BINDING_CREATE = 'create';

    /**
     * @param BindingResource $bindingResource
     */
    public function __construct(
        private readonly BindingResource $bindingResource
    ) {
    }

    /**
     * Process entity binding
     *
     * @param AbstractModel $entityToBind
     * @param string $resourceType
     * @return void
     * @throws LocalizedException
     */
    public function processEntityBinding(
        AbstractModel $entityToBind,
        string $resourceType
    ): void {
        $bindingMode = $entityToBind->getData(self::BINDING_MODE);
        if ($bindingMode === self::BINDING_SKIP) {
            return;
        }

        if ($bindingMode === self::BINDING_CREATE && !empty($entityToBind->getData(self::BINDING_DATA))) {
            $bindingData = $entityToBind->getData(self::BINDING_DATA);
            $this->bindEntity(
                $resourceType,
                (int)$bindingData[ResourceBindingInterface::ORIGINAL_RESOURCE_ID],
                (int)$entityToBind->getId(),
                (int)$bindingData[ResourceBindingInterface::STORE_ID]
            );

            return;
        }

        $this->bindEntity(
            $resourceType,
            (int)$entityToBind->getId(),
            (int)$entityToBind->getId(),
            Store::DEFAULT_STORE_ID
        );
    }

    /**
     * Bind entity
     *
     * @param string $type
     * @param int $originalId
     * @param int $translatedId
     * @param int $storeId
     * @return void
     * @throws LocalizedException
     */
    public function bindEntity(string $type, int $originalId, int $translatedId, int $storeId): void
    {
        if (!$this->bindingResource->checkIsAlreadyBound($type, $translatedId)) {
            $dataToInsert = [
                ResourceBindingInterface::RESOURCE_TYPE => $type,
                ResourceBindingInterface::ORIGINAL_RESOURCE_ID => $originalId,
                ResourceBindingInterface::TRANSLATED_RESOURCE_ID => $translatedId,
                ResourceBindingInterface::STORE_ID => $storeId
            ];

            $this->bindingResource->massSave($dataToInsert);
        }
    }

    /**
     * Remove binding
     *
     * @param string $type
     * @param int $resourceId
     * @return void
     * @throws LocalizedException
     */
    public function removeBinding(string $type, int $resourceId): void
    {
        $this->bindingResource->removeBinding($type, $resourceId);
    }
}
