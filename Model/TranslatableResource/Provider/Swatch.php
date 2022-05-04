<?php
namespace Aheadworks\Langshop\Model\TranslatableResource\Provider;

use Magento\Eav\Model\Entity\Attribute;
use Magento\Eav\Model\Entity\Attribute\Option;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory;
use Magento\Store\Model\Store;

class Swatch
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get values
     *
     * @param Attribute $attribute
     * @return array
     */
    public function getValues(Attribute $attribute): array
    {
        $collection = $this->collectionFactory->create()
            ->setAttributeFilter(
                $attribute->getId()
            )->setPositionOrder(
                'asc',
                true
            )->load();

        return $this->prepareOptionValues($attribute, $collection);
    }

    /**
     * Prepare option values
     *
     * @param Attribute $attribute
     * @param Collection $optionCollection
     * @return array
     */
    private function prepareOptionValues(Attribute $attribute, Collection $optionCollection): array
    {
        $values = [];
        $optionCollection->setPageSize(200);
        $pageCount = $optionCollection->getLastPageNumber();
        $currentPage = 1;

        while ($currentPage <= $pageCount) {
            $optionCollection->clear();
            $optionCollection->setCurPage($currentPage);
            $values = array_replace(
                $this->getPreparedValues($optionCollection, $attribute),
                $values
            );
            $currentPage++;
        }

        return $values;
    }

    /**
     * Get prepared values
     *
     * @param Collection $optionCollection
     * @param Attribute $attribute
     * @return array
     */
    private function getPreparedValues(
        Collection $optionCollection,
        Attribute $attribute
    ): array {
        $values = [];
        foreach ($optionCollection as $option) {
            $optionValues = $this->prepareAttributeOptionValues($option, $attribute);
            $values = array_replace($values, $optionValues);
        }

        return $values;
    }

    /**
     * Prepare attribute option values
     *
     * @param Option $option
     * @param Attribute $attribute
     * @return array
     */
    private function prepareAttributeOptionValues(Option $option, Attribute $attribute): array
    {
        $optionId = $option->getId();
        $storeId = $attribute->getStoreId();
        $storeValues = $this->getStoreOptionValues($attribute, $storeId);
        $value[$option->getId()] = isset($storeValues[$optionId])
            ? $storeValues[$optionId]
            : '';

        return $value;
    }

    /**
     * Retrieve attribute option values for given store id
     *
     * @param Attribute $attribute
     * @param int $storeId
     * @return array
     */
    private function getStoreOptionValues(Attribute $attribute, int $storeId): array
    {
        $values = [];
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->setAttributeFilter(
            $attribute->getId()
        );
        $this->addCollectionStoreFilter($collection, $storeId);
        $collection->getSelect()->joinLeft(
            ['swatch_table' => $collection->getTable('eav_attribute_option_swatch')],
            'swatch_table.option_id = main_table.option_id AND swatch_table.store_id = '.$storeId,
            'swatch_table.value AS label'
        );
        $collection->load();
        foreach ($collection as $item) {
            $values[$item->getId()] = $item->getLabel();
        }

        return $values;
    }

    /**
     * Add collection store filter
     *
     * @param Collection $collection
     * @param int $storeId
     * @return void
     */
    private function addCollectionStoreFilter(Collection $collection, int $storeId): void
    {
        $joinCondition = $collection->getConnection()->quoteInto(
            'tsv.option_id = main_table.option_id AND tsv.store_id = ?',
            $storeId
        );

        $select = $collection->getSelect();
        $select->joinLeft(
            ['tsv' => $collection->getTable('eav_attribute_option_value')],
            $joinCondition,
            'value'
        );
        if (Store::DEFAULT_STORE_ID == $storeId) {
            $select->where(
                'tsv.store_id = ?',
                $storeId
            );
        }
        $collection->setOrder('value', Collection::SORT_ORDER_ASC);
    }
}
