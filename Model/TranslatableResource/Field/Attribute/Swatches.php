<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Attribute;

use Aheadworks\Langshop\Model\TranslatableResource\Field\PersistorInterface;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Option as OptionValidation;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory as SwatchCollectionFactory;
use Magento\Swatches\Model\Swatch;

class Swatches implements PersistorInterface
{
    /**
     * Field to process
     */
    private const FIELD = 'swatches';

    /**
     * @var SwatchCollectionFactory
     */
    private SwatchCollectionFactory $swatchCollectionFactory;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var OptionValidation
     */
    private OptionValidation $optionValidation;

    /**
     * @param SwatchCollectionFactory $swatchCollectionFactory
     * @param ResourceConnection $resourceConnection
     * @param OptionValidation $optionValidation
     */
    public function __construct(
        SwatchCollectionFactory $swatchCollectionFactory,
        ResourceConnection $resourceConnection,
        OptionValidation $optionValidation
    ) {
        $this->swatchCollectionFactory = $swatchCollectionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->optionValidation = $optionValidation;
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
        foreach ($this->getSwatches(array_keys($items), $storeId) as $optionId => $swatch) {
            $item = $items[$swatch->getData('attribute_id')];

            $item->setData(self::FIELD, array_replace(
                $item->getData(self::FIELD) ?? [],
                [$optionId => $swatch->getData('value')]
            ));
        }
    }

    /**
     * Saves swatches for the attribute
     *
     * @param AbstractModel $item
     * @param int $storeId
     * @return void
     * @throws LocalizedException
     */
    public function save(AbstractModel $item, int $storeId): void
    {
        $swatches = $item->getData(self::FIELD);
        if (is_array($swatches) && $swatches) {
            foreach ($swatches as $optionId => $swatch) {
                $this->optionValidation->validate((int) $optionId, (int) $item->getId());
            }

            $toInsert = [];
            $existingSwatches = $this->getSwatches([$item->getId()], $storeId);

            foreach ($swatches as $optionId => $value) {
                $existingSwatch = $existingSwatches[$optionId] ?? null;

                $toInsert[] = [
                    'swatch_id' => $existingSwatch ? $existingSwatch->getId() : null,
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
            'eav_attribute_option_swatch'
        );
    }

    /**
     * @param int[] $attributeIds
     * @param int $storeId
     * @return Swatch[]
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
            $swatches[$swatch->getData('option_id')] = $swatch;
        }

        return $swatches;
    }
}
