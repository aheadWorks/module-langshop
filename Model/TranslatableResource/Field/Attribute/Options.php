<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Magento\Eav\Model\Entity\Attribute\Option;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Framework\Model\AbstractModel;

class Options implements ProcessorInterface
{
    /**
     * @var OptionCollectionFactory
     */
    private OptionCollectionFactory $optionCollectionFactory;

    /**
     * @param OptionCollectionFactory $optionCollectionFactory
     */
    public function __construct(
        OptionCollectionFactory $optionCollectionFactory
    ) {
        $this->optionCollectionFactory = $optionCollectionFactory;
    }

    /**
     * Retrieves options for the attributes
     *
     * @param AbstractModel[] $items
     * @param int $storeId
     * @return void
     */
    public function load(array $items, int $storeId): void
    {
        foreach ($this->getOptions(array_keys($items), $storeId) as $attributeId => $options) {
            $items[$attributeId]->setData('options', $options);
        }
    }

    /**
     * Saves options for the attribute
     *
     * @param AbstractModel $item
     * @param int $storeId
     * @return void
     */
    public function save(AbstractModel $item, int $storeId): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @param int[] $attributeIds
     * @param int $storeId
     * @return array
     */
    private function getOptions(array $attributeIds, int $storeId): array
    {
        $options = [];

        $optionCollection = $this->optionCollectionFactory->create()
            ->addFieldToFilter('main_table.attribute_id', $attributeIds)
            ->setStoreFilter($storeId);

        /** @var Option $option */
        foreach ($optionCollection as $option) {
            $options[$option->getAttributeId()][$option->getId()] = $option->getValue();
        }

        return $options;
    }
}
