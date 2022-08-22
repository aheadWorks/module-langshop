<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterfaceFactory;
use Aheadworks\Langshop\Model\Config\ListToTranslate as ListToTranslateConfig;
use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeType;
use Magento\Framework\Exception\NoSuchEntityException;
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
     * @var EntityPool
     */
    private EntityPool $entityPool;

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
     * @param EntityPool $entityPool
     */
    public function __construct(
        LocaleResolverInterface $localeResolver,
        RecordInterfaceFactory $localeScopeFactory,
        ListToTranslateConfig $listToTranslateConfig,
        LocaleCodeConverter $localeCodeConverter,
        StoreRepository $storeRepository,
        LocaleConfig $localeConfig,
        EntityPool $entityPool
    ) {
        $this->localeResolver = $localeResolver;
        $this->localeScopeFactory = $localeScopeFactory;
        $this->listToTranslateConfig = $listToTranslateConfig;
        $this->localeCodeConverter = $localeCodeConverter;
        $this->storeRepository = $storeRepository;
        $this->localeConfig = $localeConfig;
        $this->entityPool = $entityPool;
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
     * @param string|null $resourceType
     * @return RecordInterface
     * @throws NoSuchEntityException
     */
    public function getPrimary(string $resourceType = null): RecordInterface
    {
        $defaultLocale = $this->localeResolver->getDefaultLocale();
        if ($resourceType) {
            $defaultLocale = $this->entityPool->getByType($resourceType)->getDefaultLocale() ?: $defaultLocale;
        }

        $localeCode = $this->localeCodeConverter->toLangshop($defaultLocale);

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
