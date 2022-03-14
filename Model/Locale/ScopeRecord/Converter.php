<?php
namespace Aheadworks\Langshop\Model\Locale\ScopeRecord;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Api\Data\LocaleInterfaceFactory;
use Aheadworks\Langshop\Model\Locale\ScopeRecordInterface;
use Magento\Framework\DataObject\Copy;

class Converter
{
    /**
     * @var Copy
     */
    private $copyService;

    /**
     * @var LocaleInterfaceFactory
     */
    private $localeFactory;

    /**
     * @param Copy $copyService
     * @param LocaleInterfaceFactory $localeFactory
     */
    public function __construct(
        Copy $copyService,
        LocaleInterfaceFactory $localeFactory
    ) {
        $this->copyService = $copyService;
        $this->localeFactory = $localeFactory;
    }

    /**
     * Convert scope record to locale data model
     *
     * @param ScopeRecordInterface $scopeRecord
     * @return LocaleInterface
     */
    public function toLocaleDataModel(ScopeRecordInterface $scopeRecord)
    {
        $locale = $this->localeFactory->create();

        $this->copyService->copyFieldsetToTarget(
            'aw_ls_convert_scope_record',
            'to_locale',
            $scopeRecord,
            $locale
        );

        return $locale;
    }
}
