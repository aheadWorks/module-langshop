<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Scope\Record;

use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterface;
use Aheadworks\Langshop\Api\Data\Locale\Scope\RecordInterfaceFactory;
use Aheadworks\Langshop\Model\Config\ListToTranslate as ListToTranslateConfig;
use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Source\Locale\Scope\Type as LocaleScopeType;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreRepository;

class Repository
{
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
    private array $localeScopes;

    /**
     * @param RecordInterfaceFactory $localeScopeFactory
     * @param ListToTranslateConfig $listToTranslateConfig
     * @param StoreRepository $storeRepository
     * @param LocaleConfig $localeConfig
     */
    public function __construct(
        RecordInterfaceFactory $localeScopeFactory,
        ListToTranslateConfig $listToTranslateConfig,
        StoreRepository $storeRepository,
        LocaleConfig $localeConfig
    ) {
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
        if (!isset($this->localeScopes)) {
            $scopeIds = $this->listToTranslateConfig->getValue();

            foreach ($this->storeRepository->getList() as $scope) {
                if (in_array($scope->getId(), $scopeIds)) {
                    $this->localeScopes[] = $this->localeScopeFactory->create()
                        ->setScopeId($scope->getId())
                        ->setScopeType(LocaleScopeType::STORE)
                        ->setLocaleCode($this->localeConfig->getValue((int) $scope->getId()))
                        ->setIsPrimary(false);
                }
            }
        }

        return $this->localeScopes;
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
            ->setLocaleCode($this->localeConfig->getValue(Store::DEFAULT_STORE_ID))
            ->setIsPrimary(true);
    }
}
