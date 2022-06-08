<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute;

use Magento\Store\Model\Store;
use Magento\Swatches\Model\Swatch as AttributeSwatch;
use Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory as SwatchCollectionFactory;

class Swatch
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
     * Retrieves swatches for particular attributes
     *
     * @param int[] $attributeIds
     * @param int|null $storeId
     * @return AttributeSwatch[]
     */
    public function get(array $attributeIds, ?int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $swatches = [];

        $swatchCollection = $this->swatchCollectionFactory->create()
            ->addStoreFilter($storeId);

        $swatchCollection->join(
            ['eao' => 'eav_attribute_option'],
            'eao.option_id = main_table.option_id',
            ['attribute_id' => 'eao.attribute_id']
        )->addFieldToFilter('eao.attribute_id', $attributeIds);

        /** @var AttributeSwatch $swatch */
        foreach ($swatchCollection as $swatch) {
            $swatches[$swatch->getData('option_id')] = $swatch;
        }

        return $swatches;
    }
}
