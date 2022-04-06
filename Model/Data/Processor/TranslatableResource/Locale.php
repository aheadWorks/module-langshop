<?php
namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;

class Locale implements ProcessorInterface
{
    /**
     * Get scope id and scope type by locale code
     *
     * @param array $data
     * @return array
     */
    public function process($data)
    {
        //todo get scope id and scope type by locale code
        return $data;
    }
}
