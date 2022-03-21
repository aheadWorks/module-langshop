<?php
namespace Aheadworks\Langshop\Model\Locale;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Converter as LocaleScopeRecordConverter;
use Aheadworks\Langshop\Model\Locale\Scope\RecordInterface as LocaleScopeRecordInterface;
use Magento\Framework\Exception\LocalizedException;

class LoadHandler
{
    /**
     * @var LocaleScopeRecordConverter
     */
    private $localeScopeRecordConverter;

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @param LocaleScopeRecordConverter $localeScopeRecordConverter
     * @param ProcessorInterface $processor
     */
    public function __construct(
        LocaleScopeRecordConverter $localeScopeRecordConverter,
        ProcessorInterface $processor
    ) {
        $this->localeScopeRecordConverter = $localeScopeRecordConverter;
        $this->processor = $processor;
    }

    /**
     * @param LocaleScopeRecordInterface $scopeRecord
     * @return LocaleInterface
     * @throws LocalizedException
     */
    public function load(LocaleScopeRecordInterface $scopeRecord)
    {
        $locale = $this->localeScopeRecordConverter->toLocaleDataModel($scopeRecord);

        return $this->processor->process($locale, $scopeRecord->getData());
    }
}
