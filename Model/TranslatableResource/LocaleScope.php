<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class LocaleScope
{
    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @var RecordInterface[]
     */
    private array $localeScopes;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LocaleScopeRepository $localeScopeRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LocaleScopeRepository $localeScopeRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->localeScopeRepository = $localeScopeRepository;
    }

    /**
     * Retrieves available to translate locales
     *
     * @return RecordInterface[]
     */
    public function getList(): array
    {
        if (!isset($this->localeScopes)) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $this->localeScopes = $this->localeScopeRepository->getList($searchCriteria)->getItems();
        }

        return $this->localeScopes;
    }
}
