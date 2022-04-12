<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface TranslationInterface
{
    const LOCALE = 'locale';
    const KEY = 'key';
    const VALUE = 'value';

    /**
     * Set locale
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale);

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * Get key
     *
     * @return string
     */
    public function getKey();

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Get value
     *
     * @return string
     */
    public function getValue();
}
