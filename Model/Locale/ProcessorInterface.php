<?php
namespace Aheadworks\Langshop\Model\Locale;

use Aheadworks\Langshop\Api\Data\LocaleInterface;

interface ProcessorInterface
{
    /**
     * Process locale
     *
     * @param \Aheadworks\Langshop\Api\Data\LocaleInterface $locale
     * @param array $data
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(LocaleInterface $locale, array $data);
}
