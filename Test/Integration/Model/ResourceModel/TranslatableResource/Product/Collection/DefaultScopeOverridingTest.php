<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\ResourceModel\TranslatableResource\Product\Collection;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\App\Emulation as StoreEmulation;
use PHPUnit\Framework\TestCase;

class DefaultScopeOverridingTest extends TestCase
{
    /**
     * @var StoreEmulation|null
     */
    private ?StoreEmulation $storeEmulation;

    /**
     * @var StoreRepositoryInterface|null
     */
    private ?StoreRepositoryInterface $storeRepository;

    /**
     * @var ProductRepositoryInterface|null
     */
    private ?ProductRepositoryInterface $productRepository;

    /**
     * @var ProductCollection|null
     */
    private ?ProductCollection $productCollection;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();

        $this->storeEmulation = $objectManager->create(StoreEmulation::class);
        $this->storeRepository = $objectManager->create(StoreRepositoryInterface::class);
        $this->productRepository = $objectManager->create(ProductRepositoryInterface::class);
        $this->productCollection = $objectManager->create(ProductCollection::class);
    }

    /**
     * Store-specific values should not be overridden by default scope
     *
     * @magentoDbIsolation disabled
     * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     *
     * @throws LocalizedException
     */
    public function testGetItems(): void
    {
        $store = $this->storeRepository->get('fixturestore');
        $this->storeEmulation->startEnvironmentEmulation($store->getId());

        $product = $this->productRepository->get('simple');

        $this->productCollection
            ->setStoreId($store->getId())
            ->addIdFilter($product->getId())
            ->addFieldToSelect('name');

        $this->assertNull($this->getCollectionProduct()->getName());

        $this->productRepository->save($product);
        $this->assertSame($product->getName(), $this->getCollectionProduct()->getName());

        $this->storeEmulation->stopEnvironmentEmulation();
    }

    /**
     * @return ProductInterface
     */
    private function getCollectionProduct(): ProductInterface
    {
        /** @var ProductInterface $product */
        $product = $this->productCollection->clear()->getFirstItem();

        return $product;
    }
}
