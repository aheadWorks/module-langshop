<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation\Product;

use Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory as OptionCollectionFactory;
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
     * Validates option identifier for the particular product
     *
     * @param int $optionId
     * @param int $productId
     * @throws NoSuchEntityException
     */
    public function validate(int $optionId, int $productId): void
    {
        if (!isset($this->options[$productId])) {
            $optionCollection = $this->optionCollectionFactory->create()
                ->addProductToFilter($productId);

            $this->options[$productId] = $optionCollection->getAllIds();
        }

        if (!in_array($optionId, $this->options[$productId])) {
            throw new NoSuchEntityException(__('Option with identifier = "%1" does not exist.', $optionId));
        }
    }
}
