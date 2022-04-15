<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data;

use Magento\Framework\Exception\LocalizedException;

interface ProcessorInterface
{
    /**
     * Process data
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process(array $data): array;
}
