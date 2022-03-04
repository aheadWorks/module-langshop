<?php
namespace Aheadworks\Langshop\Model\Locale;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\ScopeRecord\Converter;

class LoadHandler
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @param Converter $converter
     */
    public function __construct(
        Converter $converter
    ) {
        $this->converter = $converter;
    }

    /**
     * @param ScopeRecordInterface $scopeRecord
     * @return LocaleInterface
     */
    public function load(ScopeRecordInterface $scopeRecord)
    {
        return $this->converter->toLocaleDataModel($scopeRecord);
    }
}
