<?php
namespace Aheadworks\Langshop\Model\Locale;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\ScopeRecord\Converter;
use Magento\Framework\Exception\LocalizedException;

class LoadHandler
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @param Converter $converter
     * @param ProcessorInterface $processor
     */
    public function __construct(
        Converter $converter,
        ProcessorInterface $processor
    ) {
        $this->converter = $converter;
        $this->processor = $processor;
    }

    /**
     * @param ScopeRecordInterface $scopeRecord
     * @throws LocalizedException
     * @return LocaleInterface
     */
    public function load(ScopeRecordInterface $scopeRecord)
    {
        $locale = $this->converter->toLocaleDataModel($scopeRecord);

        return $this->processor->process($locale, $scopeRecord->getData());
    }
}
