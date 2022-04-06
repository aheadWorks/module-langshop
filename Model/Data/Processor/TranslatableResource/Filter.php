<?php
namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Magento\Framework\Api\FilterBuilder;

class Filter implements ProcessorInterface
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        FilterBuilder $filterBuilder
    ) {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Create filters
     *
     * @param array $data
     * @return array
     */
    public function process($data)
    {
        //todo create filters

        return $data;
    }
}
