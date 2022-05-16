<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale\Processor;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Aheadworks\Langshop\Model\Locale\LocaleCodeConverter;
use Aheadworks\Langshop\Model\Locale\ProcessorInterface;
use Magento\Framework\Locale\ListsInterface;

class Name implements ProcessorInterface
{
    /**
     * @var ListsInterface
     */
    private ListsInterface $locales;

    /**
     * @var LocaleCodeConverter
     */
    private LocaleCodeConverter $localeCodeConverter;

    /**
     * @param ListsInterface $locales
     * @param LocaleCodeConverter $localeCodeConverter
     */
    public function __construct(
        ListsInterface $locales,
        LocaleCodeConverter $localeCodeConverter
    ) {
        $this->locales = $locales;
        $this->localeCodeConverter = $localeCodeConverter;
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
            $localeCode = $this->localeCodeConverter->toMagento($localeCode);

            foreach ($this->locales->getOptionLocales() as $localeOption) {
                if ($localeOption['value'] === $localeCode) {
                    $locale->setName($localeOption['label']);
                }
            }
        }

        return $locale;
    }
}
