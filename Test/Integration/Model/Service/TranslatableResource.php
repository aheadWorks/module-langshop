<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Service;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface as ResourceFilter;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface as ResourceTranslation;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as ScopeRecordRepository;
use Aheadworks\Langshop\Model\Service\TranslatableResource as ResourceService;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriter;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use PHPUnit\Framework\TestCase;

abstract class TranslatableResource extends TestCase
{
    /**
     * @var ObjectManagerInterface|null
     */
    private ?ObjectManagerInterface $objectManager;

    /**
     * @var ResourceService|null
     */
    private ?ResourceService $resourceService;

    /**
     * @var StoreRepositoryInterface|null
     */
    private ?StoreRepositoryInterface $storeRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->resourceService = $this->objectManager->create(ResourceService::class);
        $this->storeRepository = $this->objectManager->create(StoreRepositoryInterface::class);
    }

    /**
     * @throws LocalizedException
     */
    public function testGetList(): void
    {
        $entity = $this->getEntity();

        /** @var ResourceFilter $resourceFilter */
        $resourceFilter = $this->objectManager->create(ResourceFilter::class);
        $this->prepareResourceFilter($resourceFilter);

        $resourceList = $this->resourceService->getList(
            $this->getResourceType(),
            [],
            null,
            null,
            null,
            [$resourceFilter]
        );

        $this->assertCount(1, $resourceList->getItems());
        $this->assertSame($entity->getId(), $resourceList->getItems()[0]->getResourceId());
        $this->assertSame(1, $resourceList->getPagination()->getTotalItems());
    }

    /**
     * @throws LocalizedException
     */
    public function testGetById(): void
    {
        $entity = $this->getEntity();

        $resource = $this->resourceService->getById(
            $this->getResourceType(),
            (string) $entity->getId()
        );

        $this->assertSame($entity->getId(), $resource->getResourceId());
    }

    /**
     * @throws LocalizedException
     */
    public function testSave(): void
    {
        $entity = $this->getEntity();
        $this->addStoreToTranslatable($this->getStore());

        /** @var ResourceTranslation $resourceTranslation */
        $resourceTranslation = $this->objectManager->create(ResourceTranslation::class);
        $this->prepareResourceTranslation($resourceTranslation);

        $this->resourceService->save(
            $this->getResourceType(),
            (string) $entity->getId(),
            [$resourceTranslation]
        );
    }

    /**
     * @return StoreInterface
     * @throws LocalizedException
     */
    private function getStore(): StoreInterface
    {
        return $this->storeRepository->get('fixturestore');
    }

    /**
     * @param StoreInterface $store
     */
    private function addStoreToTranslatable(StoreInterface $store): void
    {
        /** @var ConfigWriter $configWriter */
        $configWriter = $this->objectManager->create(ConfigWriter::class);
        $configWriter->save('aw_ls/general/scope_list_to_translate', (string) $store->getId());

        /** @var ReinitableConfigInterface $reinitableConfig */
        $reinitableConfig = $this->objectManager->create(ReinitableConfigInterface::class);
        $reinitableConfig->reinit();

        /** @var ScopeRecordRepository $scopeRecordRepository */
        $scopeRecordRepository = $this->objectManager->get(ScopeRecordRepository::class);
        $scopeRecordRepository->clearCache();
    }

    /**
     * @return AbstractModel
     */
    abstract protected function getEntity(): AbstractModel;

    /**
     * @return string
     */
    abstract protected function getResourceType(): string;

    /**
     * @param ResourceFilter $resourceFilter
     * @return ResourceFilter
     */
    abstract protected function prepareResourceFilter(
        ResourceFilter $resourceFilter
    ): ResourceFilter;

    /**
     * @param ResourceTranslation $resourceTranslation
     * @return ResourceTranslation
     */
    abstract protected function prepareResourceTranslation(
        ResourceTranslation $resourceTranslation
    ): ResourceTranslation;
}
