<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Service\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface as ResourceFilter;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface as ResourceTranslation;
use Aheadworks\Langshop\Test\Integration\Model\Service\TranslatableResource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * @magentoDbIsolation disabled
 * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
 * @magentoDataFixture Magento/Catalog/_files/product_simple.php
 */
class ProductTest extends TranslatableResource
{
    /**
     * @var ProductRepositoryInterface|null
     */
    private ?ProductRepositoryInterface $productRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();
        $this->productRepository = $objectManager->create(ProductRepositoryInterface::class);

        parent::setUp();
    }

    /**
     * @return AbstractModel
     * @throws LocalizedException
     */
    protected function getEntity(): AbstractModel
    {
        /** @var Product $product */
        $product = $this->productRepository->get('simple');

        return $product;
    }

    /**
     * @return string
     */
    protected function getResourceType(): string
    {
        return 'product';
    }

    /**
     * @param ResourceFilter $resourceFilter
     * @return ResourceFilter
     * @throws LocalizedException
     */
    protected function prepareResourceFilter(ResourceFilter $resourceFilter): ResourceFilter
    {
        /** @var ProductInterface $product */
        $product = $this->getEntity();

        return $resourceFilter
            ->setField('name')
            ->setValue([$product->getName()]);
    }

    /**
     * @param ResourceTranslation $resourceTranslation
     * @return ResourceTranslation
     */
    protected function prepareResourceTranslation(ResourceTranslation $resourceTranslation): ResourceTranslation
    {
        return $resourceTranslation
            ->setLocale('en-US')
            ->setKey('name')
            ->setValue('test name');
    }
}
