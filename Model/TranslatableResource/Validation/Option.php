<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Option
{
    /**
     * @var OptionCollectionFactory
     */
    private OptionCollectionFactory $optionCollectionFactory;

    /**
     * @var array<int, int[]>
     */
    private array $options;

    /**
     * @param OptionCollectionFactory $optionCollectionFactory
     */
    public function __construct(
        OptionCollectionFactory $optionCollectionFactory
    ) {
        $this->optionCollectionFactory = $optionCollectionFactory;
    }

    /**
     * Validates option identifier for the particular attribute
     *
     * @param int $optionId
     * @param int $attributeId
     * @throws NoSuchEntityException
     */
    public function validate(int $optionId, int $attributeId): void
    {
        if (!isset($this->options[$attributeId])) {
            $optionCollection = $this->optionCollectionFactory->create()
                ->addFieldToFilter('attribute_id', (string) $attributeId);

            $this->options[$attributeId] = $optionCollection->getAllIds();
        }

        if (!in_array($optionId, $this->options[$attributeId])) {
            throw new NoSuchEntityException(__('Option with identifier = "%1" does not exist.', $optionId));
        }
    }
}
