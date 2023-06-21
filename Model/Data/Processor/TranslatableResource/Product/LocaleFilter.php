<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource\Product;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Langshop\Model\Entity\Field\Filter\Builder as FilterBuilder;

class LocaleFilter implements ProcessorInterface
{
    /**
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        private FilterBuilder $filterBuilder
    ) {
    }

    /**
     * Prepares filters for search criteria
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process(array $data): array
    {
        //TODO: LSM2-196 apply website ids from locales list
        $data['filter'][] = $this->filterBuilder->create(
            'website_id',
            ['2'],
            'website_id'
        );

        return $data;
    }
}
