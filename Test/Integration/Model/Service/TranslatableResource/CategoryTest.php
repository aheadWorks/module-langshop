<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Service\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface as ResourceFilter;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface as ResourceTranslation;
use Aheadworks\Langshop\Test\Integration\Model\Service\TranslatableResource;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
 * @magentoDataFixture Magento/Catalog/_files/category.php
 */
class CategoryTest extends TranslatableResource
{
    /**
     * @var CategoryRepositoryInterface|null
     */
    private ?CategoryRepositoryInterface $categoryRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();
        $this->categoryRepository = $objectManager->create(CategoryRepositoryInterface::class);

        parent::setUp();
    }

    /**
     * @return AbstractModel
     * @throws LocalizedException
     */
    protected function getEntity(): AbstractModel
    {
        /** @var Category $category */
        $category = $this->categoryRepository->get(333);

        return $category;
    }

    /**
     * @return string
     */
    protected function getResourceType(): string
    {
        return 'category';
    }

    /**
     * @param ResourceFilter $resourceFilter
     * @return ResourceFilter
     * @throws LocalizedException
     */
    protected function prepareResourceFilter(ResourceFilter $resourceFilter): ResourceFilter
    {
        /** @var CategoryInterface $category */
        $category = $this->getEntity();

        return $resourceFilter
            ->setField('name')
            ->setValue([$category->getName()]);
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
