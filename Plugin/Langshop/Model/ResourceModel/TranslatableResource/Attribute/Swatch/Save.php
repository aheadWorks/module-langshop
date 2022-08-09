<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Swatch;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute as AttributeResource;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute\Swatch as SwatchProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Attribute\Option as OptionValidation;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;

class Save
{
    /**
     * The model fields to work with
     */
    private const KEY_SWATCHES = 'swatches';
    private const KEY_STORE_ID = 'store_id';

    /**
     * @var SwatchProvider
     */
    private SwatchProvider $swatchProvider;

    /**
     * @var OptionValidation
     */
    private OptionValidation $optionValidation;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param SwatchProvider $swatchProvider
     * @param OptionValidation $optionValidation
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        SwatchProvider $swatchProvider,
        OptionValidation $optionValidation,
        ResourceConnection $resourceConnection
    ) {
        $this->swatchProvider = $swatchProvider;
        $this->optionValidation = $optionValidation;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Validates swatches before saving
     *
     * @param AttributeResource $attributeResource
     * @param Attribute $attribute
     * @return Attribute[]
     * @throws NoSuchEntityException
     */
    public function beforeSave(
        AttributeResource $attributeResource,
        Attribute $attribute
    ): array {
        $swatches = $attribute->getData(self::KEY_SWATCHES);
        if (is_array($swatches) && $swatches) {
            foreach ($swatches as $optionId => $value) {
                $this->optionValidation->validate((int) $optionId, (int) $attribute->getId());
            }
        }

        return [
            $attribute
        ];
    }

    /**
     * Saves swatches for the attribute
     *
     * @param AttributeResource $attributeResource
     * @param AttributeResource $result
     * @param Attribute $attribute
     * @return AttributeResource
     */
    public function afterSave(
        AttributeResource $attributeResource,
        AttributeResource $result,
        Attribute $attribute
    ): AttributeResource {
        $swatches = $attribute->getData(self::KEY_SWATCHES);
        if (is_array($swatches) && $swatches) {
            $storeId = (int) $attribute->getData(self::KEY_STORE_ID);
            $existingSwatches = $this->swatchProvider->get([$attribute->getId()], $storeId);

            $toInsert = [];
            $toDelete = [];

            foreach ($swatches as $optionId => $value) {
                if ($value) {
                    $toInsert[] = [
                        'swatch_id' => $existingSwatches[$optionId]->getData('swatch_id'),
                        'option_id' => $optionId,
                        'store_id' => $storeId,
                        'value' => $value
                    ];
                } else {
                    $toDelete[] = $existingSwatches[$optionId]->getData('swatch_id');
                }
            }

            if ($toInsert) {
                $this->resourceConnection->getConnection()->insertOnDuplicate(
                    $this->resourceConnection->getTableName('eav_attribute_option_swatch'),
                    $toInsert
                );
            }

            if ($toDelete) {
                $toDelete = implode(',', $toDelete);
                $this->resourceConnection->getConnection()->delete(
                    $this->resourceConnection->getTableName('eav_attribute_option_swatch'),
                    "swatch_id IN ($toDelete)"
                );
            }

        }

        return $result;
    }
}
