<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory as SwatchCollectionFactory;
use Magento\Swatches\Model\Swatch;

class Swatches implements ProcessorInterface
{
    /**
     * @var SwatchCollectionFactory
     */
    private SwatchCollectionFactory $swatchCollectionFactory;

    /**
     * @param SwatchCollectionFactory $swatchCollectionFactory
     */
    public function __construct(
        SwatchCollectionFactory $swatchCollectionFactory
    ) {
        $this->swatchCollectionFactory = $swatchCollectionFactory;
    }

    /**
     * Retrieves swatches for the attributes
     *
     * @param AbstractModel[] $items
     * @param int $storeId
     * @return void
     */
    public function load(array $items, int $storeId): void
    {
        foreach ($this->getSwatches(array_keys($items), $storeId) as $attributeId => $swatches) {
            $items[$attributeId]->setData('swatches', $swatches);
        }
    }

    /**
     * Saves swatches for the attribute
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
    private function getSwatches(array $attributeIds, int $storeId): array
    {
        $swatches = [];

        $swatchCollection = $this->swatchCollectionFactory->create()
            ->addStoreFilter($storeId);

        $swatchCollection->join(
            ['eao' => 'eav_attribute_option'],
            'eao.option_id = main_table.option_id',
            ['attribute_id' => 'eao.attribute_id']
        )->addFieldToFilter('eao.attribute_id', $attributeIds);

        /** @var Swatch $swatch */
        foreach ($swatchCollection as $swatch) {
            $swatches[$swatch->getAttributeId()][$swatch->getOptionId()] = $swatch->getValue();
        }

        return $swatches;
    }
}
