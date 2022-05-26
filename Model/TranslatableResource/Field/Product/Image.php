<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Field\Product;

use Aheadworks\Langshop\Model\TranslatableResource\Field\PersistorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Media\Config as MediaConfig;
use Magento\Framework\Model\AbstractModel;

class Image implements PersistorInterface
{
    /**
     * @var MediaConfig
     */
    private MediaConfig $mediaConfig;

    /**
     * @param MediaConfig $mediaConfig
     */
    public function __construct(
        MediaConfig $mediaConfig
    ) {
        $this->mediaConfig = $mediaConfig;
    }

    /**
     * Retrieve full path to product image
     *
     * @param Product[] $items
     * @param int $storeId
     */
    public function load(array $items, int $storeId): void
    {
        foreach ($items as $item) {
            $image = $item->getImage();
            if ($image) {
                $imageUrl = $this->mediaConfig->getMediaUrl($image);
                $item->setData('image', $imageUrl);
            }
        }
    }

    /**
     * @inheritDoc
     * @codingStandardsIgnoreStart
     */
    public function save(AbstractModel $item, int $storeId): void
    {
    }
    /* @codingStandardsIgnoreEnd */
}
