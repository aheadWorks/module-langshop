<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\ProcessorInterface;
use Magento\Eav\Model\Entity\Attribute\Option;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class Options implements ProcessorInterface
{
    /**
     * @var OptionCollectionFactory
     */
    private OptionCollectionFactory $optionCollectionFactory;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param OptionCollectionFactory $optionCollectionFactory
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        OptionCollectionFactory $optionCollectionFactory,
        ResourceConnection $resourceConnection
    ) {
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->resourceConnection = $resourceConnection;
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
        foreach ($this->getOptions(array_keys($items), $storeId) as $optionId => $option) {
            $item = $items[$option->getAttributeId()];

            $item->setData('options', array_replace(
                $item->getData('options') ?? [],
                [$optionId => $option->getValue()]
            ));
        }
    }

    /**
     * Saves options for the attribute
     *
     * @param AbstractModel $item
     * @param int $storeId
     * @return void
     * @throws LocalizedException
     */
    public function save(AbstractModel $item, int $storeId): void
    {
        $options = $item->getData('options');
        if (is_array($options)) {
            $optionIds = array_keys($this->getOptions([$item->getId()]));
            foreach ($options as $optionId => $value) {
                if (!in_array($optionId, $optionIds)) {
                    throw new LocalizedException(__('Option with identifier = "%1" does not exist.', $optionId));
                }
            }

            $toInsert = [];
            $existingOptions = $this->getOptions([$item->getId()], $storeId);

            foreach ($options as $optionId => $value) {
                $toInsert[] = [
                    'value_id' => $existingOptions[$optionId]->getData('value_id'),
                    'option_id' => $optionId,
                    'store_id' => $storeId,
                    'value' => $value
                ];
            }

            $this->resourceConnection->getConnection()->insertOnDuplicate(
                $this->getTableName(),
                $toInsert
            );
        }
    }

    /**
     * @return string
     */
    private function getTableName(): string
    {
        return $this->resourceConnection->getConnection()->getTableName(
            'eav_attribute_option_value'
        );
    }

    /**
     * @param int[] $attributeIds
     * @param int $storeId
     * @return Option[]
     */
    private function getOptions(array $attributeIds, int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $optionCollection = $this->optionCollectionFactory->create()
            ->addFieldToFilter('main_table.attribute_id', $attributeIds)
            ->setStoreFilter($storeId);

        $optionCollection->getSelect()->joinLeft(
            ['eaov' => $this->getTableName()],
            "eaov.option_id = main_table.option_id and eaov.store_id = $storeId",
            ['value_id' => 'eaov.value_id']
        )->order('option_id');

        return $optionCollection->getItems();
    }
}
