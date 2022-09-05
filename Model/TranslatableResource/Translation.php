<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\TranslationInterface;
use Magento\Framework\DataObject;

class Translation extends DataObject implements TranslationInterface
{
    /**
     * Constants for internal keys
     */
    private const LOCALE = 'locale';
    private const KEY = 'key';
    private const VALUE = 'value';

    /**
     * Set locale
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getData(self::LOCALE);
    }

    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->getData(self::KEY);
    }

    /**
     * Set value
     *
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }
}
