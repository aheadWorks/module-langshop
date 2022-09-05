<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Attribute\Option;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Attribute as AttributeResource;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Attribute\Option as OptionProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Attribute\Option as OptionValidation;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;

class Save
{
    /**
     * The model fields to work with
     */
    private const KEY_OPTIONS = 'options';
    private const KEY_STORE_ID = 'store_id';

    /**
     * @var OptionProvider
     */
    private OptionProvider $optionProvider;

    /**
     * @var OptionValidation
     */
    private OptionValidation $optionValidation;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param OptionProvider $optionProvider
     * @param OptionValidation $optionValidation
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        OptionProvider $optionProvider,
        OptionValidation $optionValidation,
        ResourceConnection $resourceConnection
    ) {
        $this->optionProvider = $optionProvider;
        $this->optionValidation = $optionValidation;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Validates options before saving
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
        $options = $attribute->getData(self::KEY_OPTIONS);
        if (is_array($options) && $options) {
            foreach ($options as $optionId => $value) {
                $this->optionValidation->validate((int) $optionId, (int) $attribute->getId());
            }
        }

        return [
            $attribute
        ];
    }

    /**
     * Saves options for the attribute
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
        $options = $attribute->getData(self::KEY_OPTIONS);
        if (is_array($options) && $options) {
            $storeId = (int) $attribute->getData(self::KEY_STORE_ID);
            $existingOptions = $this->optionProvider->get([$attribute->getId()], $storeId);

            $toInsert = [];
            $toDelete = [];

            foreach ($options as $optionId => $value) {
                $valueId = $existingOptions[$optionId]->getData('value_id');

                if ($value) {
                    $toInsert[] = [
                        'value_id' => $valueId,
                        'option_id' => $optionId,
                        'store_id' => $storeId,
                        'value' => $value
                    ];
                } elseif ($valueId) {
                    $toDelete[] = $valueId;
                }
            }

            if ($toInsert) {
                $this->resourceConnection->getConnection()->insertOnDuplicate(
                    $this->resourceConnection->getTableName('eav_attribute_option_value'),
                    $toInsert
                );
            }

            if ($toDelete) {
                $toDelete = implode(',', $toDelete);
                $this->resourceConnection->getConnection()->delete(
                    $this->resourceConnection->getTableName('eav_attribute_option_value'),
                    "value_id IN ($toDelete)"
                );
            }
        }

        return $result;
    }
}
