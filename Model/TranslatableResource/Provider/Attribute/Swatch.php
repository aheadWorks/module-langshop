<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute;

use Magento\Eav\Model\Entity\Attribute\Option as AttributeOption;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Store\Model\Store;

class Swatch
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
     * Retrieves swatches for particular attributes
     *
     * @param int[] $attributeIds
     * @param int $storeId
     * @return AttributeOption[]
     */
    public function get(array $attributeIds, int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $optionCollection = $this->optionCollectionFactory->create()
            ->addFieldToFilter('main_table.attribute_id', $attributeIds);

        $optionCollection->getSelect()->joinLeft(
            ['eaov' => $optionCollection->getTable('eav_attribute_option_swatch')],
            "eaov.option_id = main_table.option_id and eaov.store_id = $storeId",
            ['swatch_id', 'value']
        )->joinLeft(
            ['ceava' => $optionCollection->getTable('catalog_eav_attribute')],
            "main_table.attribute_id = ceava.attribute_id",
            []
        )->where('ceava.additional_data NOT LIKE ?', '%"swatch_input_type":"visual"%');

        /** @var AttributeOption[] $swatches */
        $swatches = $optionCollection->getItems();

        return $swatches;
    }
}
