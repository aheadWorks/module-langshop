<?php
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
    public function process($data)
    {
        if (!isset($data['page'])) {
            $data['page'] = 1;
        }
        if (!isset($data['pageSize'])) {
            $data['pageSize'] = 20;
        }

        return $data;
    }
}
