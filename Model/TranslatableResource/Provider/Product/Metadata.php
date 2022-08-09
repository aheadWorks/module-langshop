<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Provider\Product;

use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\EntityManager\MetadataPool;

class Metadata
{
    /**
     * @var MetadataPool
     */
    private MetadataPool $metadataPool;

    /**
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        MetadataPool $metadataPool
    ) {
        $this->metadataPool = $metadataPool;
    }

    /**
     * The relation field differs through CE and EE versions
     *
     * @return string
     * @throws Exception
     */
    public function getLinkField(): string
    {
        $productMetadata = $this->metadataPool->getMetadata(
            ProductInterface::class
        );

        return $productMetadata->getLinkField();
    }
}
