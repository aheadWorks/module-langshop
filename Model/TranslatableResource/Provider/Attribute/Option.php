<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute;

use Magento\Eav\Model\Entity\Attribute\Option as AttributeOption;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Store\Model\Store;

class Option
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
     * Retrieves options for particular attributes
     *
     * @param array $attributeIds
     * @param int|null $storeId
     * @return AttributeOption[]
     */
    public function get(array $attributeIds, ?int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $optionCollection = $this->optionCollectionFactory->create()
            ->addFieldToFilter('main_table.attribute_id', $attributeIds)
            ->setStoreFilter($storeId);

        $optionCollection->getSelect()->joinLeft(
            ['eaov' => $optionCollection->getTable('eav_attribute_option_value')],
            "eaov.option_id = main_table.option_id and eaov.store_id = $storeId",
            ['value_id' => 'eaov.value_id']
        )->order('option_id');

        /** @var AttributeOption[] $options */
        $options = $optionCollection->getItems();

        return $options;
    }
}
