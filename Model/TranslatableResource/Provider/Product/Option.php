<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Product;

use Magento\Catalog\Model\Product\Option as ProductOption;
use Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory as OptionCollectionFactory;
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
     * Retrieves options for particular products
     *
     * @param int[] $productIds
     * @param int $storeId
     * @return ProductOption[]
     */
    public function get(array $productIds, int $storeId = Store::DEFAULT_STORE_ID): array
    {
        $optionCollection = $this->optionCollectionFactory->create()
            ->addProductToFilter($productIds)
            ->addTitleToResult($storeId)
            ->addValuesToResult($storeId);

        /** @var ProductOption[] $options */
        $options = $optionCollection->getItems();

        return $options;
    }
}
