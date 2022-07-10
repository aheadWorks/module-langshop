<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Service\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\FilterInterface as ResourceFilter;
use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface as ResourceTranslation;
use Aheadworks\Langshop\Test\Integration\Model\Service\TranslatableResource;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
 * @magentoDataFixture Magento/Catalog/_files/product_dropdown_attribute.php
 */
class AttributeTest extends TranslatableResource
{
    /**
     * @var ProductAttributeRepositoryInterface|null
     */
    private ?ProductAttributeRepositoryInterface $attributeRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();
        $this->attributeRepository = $objectManager->create(ProductAttributeRepositoryInterface::class);

        parent::setUp();
    }

    /**
     * @return AbstractModel
     * @throws LocalizedException
     */
    protected function getEntity(): AbstractModel
    {
        /** @var Attribute $attribute */
        $attribute = $this->attributeRepository->get('dropdown_attribute');

        return $attribute;
    }

    /**
     * @return string
     */
    protected function getResourceType(): string
    {
        return 'attribute';
    }

    /**
     * @param ResourceFilter $resourceFilter
     * @return ResourceFilter
     * @throws LocalizedException
     */
    protected function prepareResourceFilter(ResourceFilter $resourceFilter): ResourceFilter
    {
        /** @var ProductAttributeInterface $attribute */
        $attribute = $this->getEntity();

        return $resourceFilter
            ->setField('frontend_label')
            ->setValue([$attribute->getDefaultFrontendLabel()]);
    }

    /**
     * @param ResourceTranslation $resourceTranslation
     * @return ResourceTranslation
     */
    protected function prepareResourceTranslation(ResourceTranslation $resourceTranslation): ResourceTranslation
    {
        return $resourceTranslation
            ->setLocale('en-US')
            ->setKey('store_label')
            ->setValue('test store label');
    }
}
