<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Processor;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;

class Composite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private array $processorList;

    /**
     * @param ProcessorInterface[] $processorList
     */
    public function __construct(
        array $processorList = []
    ) {
        $this->processorList = $processorList;
    }

    /**
     * @inheritDoc
     */
    public function process(LocaleInterface $locale, array $data = []): LocaleInterface
    {
        foreach ($this->processorList as $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new LocalizedException(
                    __('Data processor must implement %1', ProcessorInterface::class)
                );
            }
            $processor->process($locale, $data);
        }

        return $locale;
    }
}
