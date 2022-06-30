<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Collection;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\CollectionInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\EntityAttribute as EntityAttributeProvider;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class SelectLabel
{
    /**
     * @var EntityAttributeProvider
     */
    private EntityAttributeProvider $attributeProvider;

    /**
     * @param EntityAttributeProvider $attributeProvider
     */
    public function __construct(
        EntityAttributeProvider $attributeProvider
    ) {
        $this->attributeProvider = $attributeProvider;
    }

    /**
     * Replace select identifiers to labels
     *
     * @param Collection $collection
     * @param DataObject $model
     * @return DataObject
     * @throws LocalizedException
     */
    public function afterGetItemById(
        Collection $collection,
        DataObject $model
    ): DataObject {
        /** @var Collection|CollectionInterface $collection */
        $resourceType = $collection->getResourceType();

        if ($resourceType) {
            $attributes = $this->attributeProvider->getList($resourceType);

            foreach ($attributes as $attribute) {
                $options = $this->getOptions($attribute);

                if ($options) {
                    $value = $model->getData($attribute->getCode());
                    $model->setData($attribute->getCode(), $options[$value] ?? $value);
                }
            }
        }

        return $model;
    }

    /**
     * Retrieve options for the attribute
     *
     * @param Field $attribute
     * @return array
     */
    private function getOptions(Field $attribute): array
    {
        $options = [];
        if ($attribute->getFilterOptions()) {
            foreach ($attribute->getFilterOptions()->toOptionArray() as $option) {
                $options[$option['value']] = $option['label'];
            }
        }

        return $options;
    }
}
