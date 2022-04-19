<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Locale;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Magento\Framework\Exception\LocalizedException;

interface ProcessorInterface
{
    /**
     * Process locale
     *
     * @param LocaleInterface $locale
     * @param array $data
     * @return LocaleInterface
     * @throws LocalizedException
     */
    public function process(LocaleInterface $locale, array $data): LocaleInterface;
}
