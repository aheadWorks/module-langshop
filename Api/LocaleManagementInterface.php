<?php
namespace Aheadworks\Langshop\Api;

interface LocaleManagementInterface
{
    /**
     * Add locale
     *
     * @param \Aheadworks\Langshop\Api\Data\LocaleInterface $locale
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface
     */
    public function add($locale);

    /**
     * Update locale
     *
     * @param \Aheadworks\Langshop\Api\Data\LocaleInterface $locale
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface
     */
    public function update($locale);

    /**
     * Delete locale
     *
     * @param \Aheadworks\Langshop\Api\Data\LocaleInterface $locale
     * @return bool
     */
    public function delete($locale);

    /**
     * Retrieve locales
     *
     * @return \Aheadworks\Langshop\Api\Data\LocaleInterface[]
     */
    public function getList();
}
