<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

interface LocaleManagementInterface
{
    /**
     * Add locale
     *
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function add();

    /**
     * Update locale
     *
     * @param string $locale
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function update($locale);

    /**
     * Delete locale
     *
     * @param string $locale
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function delete($locale);

    /**
     * Retrieve locales
     *
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getList(): array;
}
