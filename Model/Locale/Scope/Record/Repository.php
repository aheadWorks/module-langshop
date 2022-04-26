<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterfaceFactory;
use Aheadworks\Langshop\Model\Config\ListToTranslate as ListToTranslateConfig;
use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeType;
use Magento\Framework\Locale\ResolverInterface as LocaleResolverInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreRepository;

class Repository
{
    /**
     * @var LocaleResolverInterface
     */
    private LocaleResolverInterface $localeResolver;

    /**
     * @var RecordInterfaceFactory
     */
    private RecordInterfaceFactory $localeScopeFactory;

    /**
     * @var ListToTranslateConfig
     */
    private ListToTranslateConfig $listToTranslateConfig;

    /**
     * @var StoreRepository
     */
    private StoreRepository $storeRepository;

    /**
     * @var LocaleConfig
     */
    private LocaleConfig $localeConfig;

    /**
     * @var array
     */
    private array $localeScopes = [];

    /**
     * @param LocaleResolverInterface $localeResolver
     * @param RecordInterfaceFactory $localeScopeFactory
     * @param ListToTranslateConfig $listToTranslateConfig
     * @param StoreRepository $storeRepository
     * @param LocaleConfig $localeConfig
     */
    public function __construct(
        LocaleResolverInterface $localeResolver,
        RecordInterfaceFactory $localeScopeFactory,
        ListToTranslateConfig $listToTranslateConfig,
        StoreRepository $storeRepository,
        LocaleConfig $localeConfig
    ) {
        $this->localeResolver = $localeResolver;
        $this->localeScopeFactory = $localeScopeFactory;
        $this->listToTranslateConfig = $listToTranslateConfig;
        $this->storeRepository = $storeRepository;
        $this->localeConfig = $localeConfig;
    }

    /**
     * Retrieves list of locale scopes
     *
     * @return RecordInterface[]
     */
    public function getList(): array
    {
        if (empty($this->localeScopes)) {
            $scopeIds = $this->listToTranslateConfig->getValue();

            foreach ($this->storeRepository->getList() as $scope) {
                $scopeId = $scope->getId();
                if (in_array($scopeId, $scopeIds)) {
                    $this->localeScopes[$scopeId] = $this->localeScopeFactory->create()
                        ->setScopeId($scopeId)
                        ->setScopeType(LocaleScopeType::STORE)
                        ->setLocaleCode($this->localeConfig->getValue((int) $scopeId))
                        ->setIsPrimary(false);
                }
            }
        }

        return $this->localeScopes;
    }

    /**
     * Retrieves locale scopes by locale codes
     *
     * @param string[] $locales
     * @return RecordInterface[]
     */
    public function getByLocale(array $locales): array
    {
        return array_filter(
            $this->getList(),
            fn (RecordInterface $localeScope) => in_array($localeScope->getLocaleCode(), $locales)
        );
    }

    /**
     * Retrieves primary locale scope
     *
     * @return RecordInterface
     */
    public function getPrimary(): RecordInterface
    {
        return $this->localeScopeFactory->create()
            ->setScopeId(Store::DEFAULT_STORE_ID)
            ->setScopeType(LocaleScopeType::DEFAULT)
            ->setLocaleCode($this->localeResolver->getDefaultLocale())
            ->setIsPrimary(true);
    }
}
