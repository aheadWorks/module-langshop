<?php
namespace Aheadworks\Langshop\Api;

interface LocaleManagementInterface
{
    /**
     * Add locale
     *
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     */
    public function add();

    /**
     * Update locale
     *
     * @param string $locale
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     */
    public function update($locale);

    /**
     * Delete locale
     *
     * @param string $locale
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     */
    public function delete($locale);

    /**
     * Retrieve locales
     *
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     */
    public function getList();
}
