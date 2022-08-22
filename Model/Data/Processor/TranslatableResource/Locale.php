<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Data\Processor\TranslatableResource;

use Aheadworks\Langshop\Model\Data\ProcessorInterface;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\TranslatableResource\Validation\Locale as LocaleValidation;
use Magento\Framework\Exception\NoSuchEntityException;

class Locale implements ProcessorInterface
{
    /**
     * @var LocaleValidation
     */
    private LocaleValidation $localeValidation;

    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @param LocaleValidation $localeValidation
     * @param LocaleScopeRepository $localeScopeRepository
     */
    public function __construct(
        LocaleValidation $localeValidation,
        LocaleScopeRepository $localeScopeRepository
    ) {
        $this->localeValidation = $localeValidation;
        $this->localeScopeRepository = $localeScopeRepository;
    }

    /**
     * Validation and processing incoming locales
     *
     * @param array $data
     * @return array
     * @throws NoSuchEntityException
     */
    public function process(array $data): array
    {
        $resourceType = $data['resourceType'];
        $locales = &$data['locale'];

        foreach ($locales as $locale) {
            $this->localeValidation->validate($locale, true, $resourceType);
        }

        $locales = $this->localeScopeRepository->getByLocale($locales) ?:
            [$this->localeScopeRepository->getPrimary($resourceType)];

        return $data;
    }
}
