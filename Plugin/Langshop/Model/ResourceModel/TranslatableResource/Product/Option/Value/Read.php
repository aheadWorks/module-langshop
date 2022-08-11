<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option\Value;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Option as OptionProvider;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option\Value as OptionValue;

class Read
{
    /**
     * The model fields to work with
     */
    private const KEY_OPTIONS_VALUES = 'options_values';
    private const KEY_STORE_TITLE = 'store_title';

    /**
     * @var OptionProvider
     */
    private OptionProvider $optionProvider;

    /**
     * @param OptionProvider $optionProvider
     */
    public function __construct(
        OptionProvider $optionProvider
    ) {
        $this->optionProvider = $optionProvider;
    }

    /**
     * Retrieves option values for the products
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
            $options = $this->optionProvider->get(
                array_keys($products),
                $productCollection->getStoreId()
            );

            foreach ($options as $option) {
                $product = $products[$option->getProductId()];

                if ($option->getValues()) {
                    $optionValues = [];

                    /** @var OptionValue $optionValue */
                    foreach ($option->getValues() as $optionValue) {
                        $optionValues[$optionValue->getOptionTypeId()] =
                            $optionValue->getData(self::KEY_STORE_TITLE);
                    }

                    $product->setData(self::KEY_OPTIONS_VALUES, array_replace(
                        $product->getData(self::KEY_OPTIONS_VALUES) ?? [],
                        $optionValues
                    ));
                }
            }
        }

        return $products;
    }
}
