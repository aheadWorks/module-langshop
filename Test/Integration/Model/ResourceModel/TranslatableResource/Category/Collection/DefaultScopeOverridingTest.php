<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\ResourceModel\TranslatableResource\Category\Collection;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Category\Collection as CategoryCollection;
use Aheadworks\Langshop\Test\Integration\Fixture\Store as StoreFixture;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\App\Emulation as StoreEmulation;
use PHPUnit\Framework\TestCase;

class DefaultScopeOverridingTest extends TestCase
{
    /**
     * @var StoreFixture|null
     */
    private ?StoreFixture $storeFixture;

    /**
     * @var StoreEmulation|null
     */
    private ?StoreEmulation $storeEmulation;

    /**
     * @var CategoryRepositoryInterface|null
     */
    private ?CategoryRepositoryInterface $categoryRepository;

    /**
     * @var CategoryCollection|null
     */
    private ?CategoryCollection $categoryCollection;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();

        $this->storeFixture = $objectManager->create(StoreFixture::class);
        $this->storeEmulation = $objectManager->create(StoreEmulation::class);
        $this->categoryRepository = $objectManager->create(CategoryRepositoryInterface::class);
        $this->categoryCollection = $objectManager->create(CategoryCollection::class);
    }

    /**
     * Store-specific values should not be overridden by default scope
     *
     * @return void
     * @throws LocalizedException
     */
    public function testGetItems(): void
    {
        $store = $this->storeFixture->create();
        $this->storeEmulation->startEnvironmentEmulation($store->getId());

        $category = $this->categoryRepository->get(2);

        $this->categoryCollection
            ->setStoreId($store->getId())
            ->addIdFilter([$category->getId()])
            ->addFieldToSelect('name');

        $this->assertNull($this->getCollectionCategory()->getName());

        $this->categoryRepository->save($category);
        $this->assertSame($category->getName(), $this->getCollectionCategory()->getName());

        $this->storeEmulation->stopEnvironmentEmulation();
    }

    /**
     * @return CategoryInterface
     */
    private function getCollectionCategory(): CategoryInterface
    {
        /** @var CategoryInterface $category */
        $category = $this->categoryCollection->clear()->getFirstItem();

        return $category;
    }
}
