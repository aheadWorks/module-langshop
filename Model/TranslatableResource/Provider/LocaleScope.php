<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;

class LocaleScope
{
    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @var RecordInterface[]
     */
    private array $localeScopes;

    /**
     * @param LocaleScopeRepository $localeScopeRepository
     */
    public function __construct(
        LocaleScopeRepository $localeScopeRepository
    ) {
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
            $this->localeScopes = $this->localeScopeRepository->getList();
        }

        return $this->localeScopes;
    }

    /**
     * Retrieves locale scopes by locale codes or primary flag
     *
     * @param string[] $locales
     * @return RecordInterface[]
     */
    public function getByLocale(array $locales): array
    {
        $searchByLocales = fn (RecordInterface $localeScope): bool => in_array($localeScope->getLocaleCode(), $locales);
        $searchByPrimary = fn (RecordInterface $localeScope): bool => (bool) $localeScope->getIsPrimary();

        return array_filter($this->getList(), $searchByLocales) ?:
            array_filter($this->getList(), $searchByPrimary);
    }
}
