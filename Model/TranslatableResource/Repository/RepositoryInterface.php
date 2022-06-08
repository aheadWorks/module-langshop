<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Repository;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb as Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface RepositoryInterface
{
    /**
     * Retrieve entities matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param RecordInterface[] $localeScopes
     * @return Collection
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria, array $localeScopes): Collection;

    /**
     * Get entity
     *
     * @param string $entityId
     * @param RecordInterface[] $localeScopes
     * @return DataObject
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function get(string $entityId, array $localeScopes): DataObject;

    /**
     * Save entity
     *
     * @param string $entityId
     * @param TranslationInterface[] $translations
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function save(string $entityId, array $translations): void;
}
