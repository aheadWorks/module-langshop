<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\ResourceModel\TranslatableResource\Product\Option;

use Aheadworks\Langshop\Model\ResourceModel\TranslatableResource\Product\Collection as ProductCollection;
use Aheadworks\Langshop\Model\TranslatableResource\Provider\Product\Option as OptionProvider;
use Magento\Catalog\Model\Product;

class Read
{
    /**
     * Field to process
     */
    private const KEY_FIELD = 'options';

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
            $options = $this->optionProvider->get(
                array_keys($products),
                $productCollection->getStoreId()
            );

            foreach ($options as $optionId => $option) {
                $product = $products[$option->getProductId()];

                $product->setData(self::KEY_FIELD, array_replace(
                    $product->getData(self::KEY_FIELD) ?? [],
                    [$optionId => $option->getTitle()]
                ));
            }
        }

        return $products;
    }
}
