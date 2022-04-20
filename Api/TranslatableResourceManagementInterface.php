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
     * @param mixed $locale
     * @param int|null $page
     * @param int|null $pageSize
     * @param string|null $sortBy
     * @param string|null $orderBy
     * @param string[]|null $filter
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     * @throws LocalizedException
     */
    public function getList(
        string $resourceType,
        $locale = [],
        ?int $page = null,
        ?int $pageSize = null,
        ?string $sortBy = null,
        ?string $orderBy = null,
        ?array $filter = []
    ): ResourceListInterface;

    /**
     * Retrieves translatable resource by type and identifier
     *
     * @param string $resourceType
     * @param int $resourceId
     * @param mixed $locale
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     * @throws LocalizedException
     */
    public function getById(string $resourceType, int $resourceId, $locale = []): TranslatableResourceInterface;

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
