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
     * @param bool $includePrimary
     * @param string|null $resourceType
     * @throws NoSuchEntityException
     */
    public function validate(
        string $value,
        bool $includePrimary = false,
        string $resourceType = null
    ): void {
        if (!$this->localeScopeRepository->getByLocale([$value], $includePrimary, $resourceType)) {
            throw new NoSuchEntityException(
                __('Locale with code = "%1" does not exist.', $value)
            );
        }
    }
}
