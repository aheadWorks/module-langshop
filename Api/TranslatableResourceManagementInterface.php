<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;

interface TranslatableResourceManagementInterface
{
    /**
     * Retrieves all translatable resources by specific type
     *
     * @param string $resourceType
     * @param string[] $locale
     * @param int|null $page
     * @param int|null $pageSize
     * @param string|null $sortBy
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface[]|null $filter
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResource\ResourceListInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getList(
        string $resourceType,
        array $locale = [],
        ?int $page = null,
        ?int $pageSize = null,
        ?string $sortBy = null,
        ?array $filter = []
    ): ResourceListInterface;

    /**
     * Retrieves translatable resource by type and identifier
     *
     * @param string $resourceType
     * @param int $resourceId
     * @param string[] $locale
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getById(string $resourceType, int $resourceId, array $locale = []): TranslatableResourceInterface;

    /**
     * Saves translatable resource
     *
     * @param string $resourceType
     * @param int $resourceId
     * @param \Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface[] $translations
     * @return \Aheadworks\Langshop\Api\Data\TranslatableResourceInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function save(string $resourceType, int $resourceId, array $translations): TranslatableResourceInterface;
}
