<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\TranslatableResource\Provider\LocaleScope as LocaleScopeProvider;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Locale as LocaleValidation;
use Aheadworks\Langshop\Model\Data\ProcessorInterface;

class Locale implements ProcessorInterface
{
    /**
     * @var LocaleValidation
     */
    private LocaleValidation $localeValidation;

    /**
     * @var LocaleScopeProvider
     */
    private LocaleScopeProvider $localeScopeProvider;

    /**
     * @param LocaleValidation $localeValidation
     * @param LocaleScopeProvider $localeScopeProvider
     */
    public function __construct(
        LocaleValidation $localeValidation,
        LocaleScopeProvider $localeScopeProvider
    ) {
        $this->localeValidation = $localeValidation;
        $this->localeScopeProvider = $localeScopeProvider;
    }

    /**
     * Validation and processing incoming locales
     *
     * @param array $data
     * @return array
     */
    public function process(array $data): array
    {
        $locales = &$data['locale'];

        $locales = is_array($locales) ? $locales : [$locales];
        array_map([$this->localeValidation, 'validate'], $locales);

        $locales = $this->localeScopeProvider->getByLocale($locales);

        return $data;
    }
}
