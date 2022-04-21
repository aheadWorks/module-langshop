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
        return $this->localeScopeRepository->getList();
    }

    /**
     * Retrieves locale scopes by locale codes or primary flag
     *
     * @param string[] $locales
     * @return RecordInterface[]
     */
    public function getByLocale(array $locales): array
    {
        $localeScopes = [];
        foreach ($this->getList() as $localeScope) {
            if (in_array($localeScope->getLocaleCode(), $locales)) {
                $localeScopes[] = $localeScope;
            }
        }

        return $localeScopes ?: [$this->localeScopeRepository->getPrimary()];
    }
}
