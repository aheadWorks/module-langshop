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
     * @inheritDoc
     */
    public function setLocale($locale)
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * @inheritDoc
     */
    public function getLocale()
    {
        return $this->getData(self::LOCALE);
    }

    /**
     * @inheritDoc
     */
    public function setKey($key)
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->getData(self::KEY);
    }

    /**
     * @inheritDoc
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }
}
