<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource\Validation;

use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class Locale
{
    /**
     * @var LocaleScopeRepository
     */
    private LocaleScopeRepository $localeScopeRepository;

    /**
     * @param LocaleScopeRepository $localeScopeRepository
     */
    public function __construct(
        LocaleScopeRepository $localeScopeRepository
    ) {
        $this->localeScopeRepository = $localeScopeRepository;
    }

    /**
     * Validates locale code
     *
     * @param string $value
     * @throws NoSuchEntityException
     */
    public function validate(string $value): void
    {
        if (!$this->localeScopeRepository->getByLocale([$value])) {
            throw new NoSuchEntityException(
                __('Locale with code = "%1" does not exist.', $value)
            );
        }
    }
}
