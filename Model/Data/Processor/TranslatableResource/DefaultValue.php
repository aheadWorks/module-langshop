<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;

class DefaultValue implements ProcessorInterface
{
    /**
     * Set default values for unspecified params
     *
     * @param array $data
     * @return array
     */
    public function process(array $data): array
    {
        $data['page'] = $data['page'] ?? 1;
        $data['pageSize'] = $data['pageSize'] ?? 20;

        return $data;
    }
}
