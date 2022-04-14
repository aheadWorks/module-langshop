<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Magento\Framework\Exception\LocalizedException;

interface TranslatableResourceManagementInterface
{
    /**
     * Retrieves all translatable resources by specific type
     *
     * @param string $resourceType
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     * @throws LocalizedException
     */
    public function getList(string $resourceType): ResourceListInterface;

    /**
     * Retrieves translatable resource by type and identifier
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     * @throws LocalizedException
     */
    public function getById(string $resourceType, int $resourceId): TranslatableResourceInterface;

    /**
     * Saves translatable resource
     *
     * @param string $resourceType
     * @param int $resourceId
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface[] $translations
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     * @throws LocalizedException
     */
    public function save(string $resourceType, int $resourceId, array $translations): TranslatableResourceInterface;
}
