<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Langshop\Model\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResourceInterface;
use Aheadworks\Langshop\Model\Config\Locale as LocaleConfig;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\TranslatableResource\Converter;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreRepository;

class ConverterPlugin
{
    /**
     * @param StoreRepository $storeRepository
     * @param LocaleCodeConverter $localeCodeConverter
     * @param LocaleConfig $localeConfig
     */
    public function __construct(
        private readonly StoreRepository $storeRepository,
        private readonly LocaleCodeConverter $localeCodeConverter,
        private readonly LocaleConfig $localeConfig
    ) {
    }

    /**
     * Add the locales assigned to the product resources
     *
     * @param Converter $converter
     * @param TranslatableResourceInterface $result
     * @param DataObject $item
     * @param string $resourceType
     * @return TranslatableResourceInterface
     * @throws NoSuchEntityException
     */
    public function afterConvert(
        Converter $converter,
        TranslatableResourceInterface $result,
        DataObject $item,
        string $resourceType
    ): TranslatableResourceInterface {
        if ($resourceType === 'product') {
            $resourceAssignedLocales = [];
            /** @var Product $item */
            foreach ($item->getStoreIds() as $storeId) {
                $store = $this->storeRepository->getById($storeId);
                $localeCode = $this->localeCodeConverter->toLangshop(
                    $this->localeConfig->getValue((int) $store->getId())
                );
                $resourceAssignedLocales[] = $localeCode;
            }
            $result->setResourceAssignedLocales(array_unique($resourceAssignedLocales));
        }

        return $result;
    }
}
