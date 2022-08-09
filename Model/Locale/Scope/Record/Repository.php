<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterfaceFactory;
use Aheadworks\Langshop\Model\Config\ListToTranslate as ListToTranslateConfig;
use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
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
     * @var LocaleCodeConverter
     */
    private LocaleCodeConverter $localeCodeConverter;

    /**
     * @var StoreRepository
     */
    private StoreRepository $storeRepository;

    /**
     * @var LocaleConfig
     */
    private LocaleConfig $localeConfig;

    /**
     * @var array|null
     */
    private ?array $localeScopes;

    /**
     * @param LocaleResolverInterface $localeResolver
     * @param RecordInterfaceFactory $localeScopeFactory
     * @param ListToTranslateConfig $listToTranslateConfig
     * @param LocaleCodeConverter $localeCodeConverter
     * @param StoreRepository $storeRepository
     * @param LocaleConfig $localeConfig
     */
    public function __construct(
        LocaleResolverInterface $localeResolver,
        RecordInterfaceFactory $localeScopeFactory,
        ListToTranslateConfig $listToTranslateConfig,
        LocaleCodeConverter $localeCodeConverter,
        StoreRepository $storeRepository,
        LocaleConfig $localeConfig
    ) {
        $this->localeResolver = $localeResolver;
        $this->localeScopeFactory = $localeScopeFactory;
        $this->listToTranslateConfig = $listToTranslateConfig;
        $this->localeCodeConverter = $localeCodeConverter;
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
        if (!isset($this->localeScopes)) {
            $this->localeScopes = [];
            $scopeIds = $this->listToTranslateConfig->getValue();

            foreach ($this->storeRepository->getList() as $scope) {
                if (in_array($scope->getId(), $scopeIds)) {
                    $localeCode = $this->localeCodeConverter->toLangshop(
                        $this->localeConfig->getValue((int) $scope->getId())
                    );

                    $this->localeScopes[] = $this->localeScopeFactory->create()
                        ->setScopeId($scope->getId())
                        ->setScopeType(LocaleScopeType::STORE)
                        ->setLocaleCode($localeCode)
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
     * @param bool $includePrimary
     * @return RecordInterface[]
     */
    public function getByLocale(array $locales, bool $includePrimary = false): array
    {
        $localeScopes = $this->getList();
        if ($includePrimary) {
            array_unshift($localeScopes, $this->getPrimary());
        }

        return array_filter(
            $localeScopes,
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
        $localeCode = $this->localeCodeConverter->toLangshop(
            $this->localeResolver->getDefaultLocale()
        );

        return $this->localeScopeFactory->create()
            ->setScopeId(Store::DEFAULT_STORE_ID)
            ->setScopeType(LocaleScopeType::DEFAULT)
            ->setLocaleCode($localeCode)
            ->setIsPrimary(true);
    }

    /**
     * Clears scopes caches, required after changing configuration
     */
    public function clearCache(): void
    {
        $this->localeScopes = null;
    }
}
