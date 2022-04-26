<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\TranslatableResource\Field;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

interface ProcessorInterface
{
    /**
     * Process data
     *
     * @param DataObject $object
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process(DataObject $object, array $data): array;
}
