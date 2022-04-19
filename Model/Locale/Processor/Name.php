<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Processor;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\ProcessorInterface;
use Magento\Framework\Locale\ListsInterface;

class Name implements ProcessorInterface
{
    /**
     * @var ListsInterface
     */
    private ListsInterface $locales;

    /**
     * @param ListsInterface $locales
     */
    public function __construct(
        ListsInterface $locales
    ) {
        $this->locales = $locales;
    }

    /**
     * Adds locale name to the model by its code
     *
     * @param LocaleInterface $locale
     * @param array $data
     * @return LocaleInterface
     */
    public function process(LocaleInterface $locale, array $data): LocaleInterface
    {
        $localeCode = $locale->getLocale();
        if ($localeCode) {
            foreach ($this->locales->getOptionLocales() as $localeOption) {
                if ($localeOption['value'] === $localeCode) {
                    $locale->setName($localeOption['label']);
                }
            }
        }

        return $locale;
    }
}
