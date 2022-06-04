<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory as OptionCollectionFactory;

class Read
{
    /**
     * Field to process
     */
    private const FIELD = 'options';

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
     * Retrieves options for the products
     *
     * @param ProductCollection $productCollection
     * @param Product[] $products
     * @return Product[]
     */
    public function afterGetItems(
        ProductCollection $productCollection,
        array $products
    ): array {
        if ($products) {
            $options = $this->getOptions(
                array_keys($products),
                $productCollection->getStoreId()
            );

            foreach ($options as $optionId => $option) {
                $product = $products[$option->getProductId()];

                $product->setData(self::FIELD, array_replace(
                    $product->getData(self::FIELD) ?? [],
                    [$optionId => $option->getTitle()]
                ));
            }
        }

        return $products;
    }

    /**
     * Retrieves options from database
     *
     * @param int[] $productIds
     * @param int $storeId
     * @return Option[]
     */
    private function getOptions(array $productIds, int $storeId): array
    {
        $optionCollection = $this->optionCollectionFactory->create()
            ->addProductToFilter($productIds)
            ->addTitleToResult($storeId);

        /** @var Option[] $options */
        $options = $optionCollection->getItems();

        return $options;
    }
}
