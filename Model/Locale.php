<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Magento\Framework\DataObject;

class Locale extends DataObject implements LocaleInterface
{
    /**
     * Constants for internal keys
     */
    private const LOCALE = 'locale';
    private const NAME = 'name';
    private const PRIMARY = 'primary';
    private const PUBLISHED = 'published';
    private const URL = 'url';
    private const PREVIEW_URL = 'preview_url';

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
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set primary
     *
     * @param bool $primary
     * @return $this
     */
    public function setPrimary($primary)
    {
        return $this->setData(self::PRIMARY, $primary);
    }

    /**
     * Get primary
     *
     * @return bool
     */
    public function getPrimary()
    {
        return $this->getData(self::PRIMARY);
    }

    /**
     * Set published
     *
     * @param bool $published
     * @return $this
     */
    public function setPublished($published)
    {
        return $this->setData(self::PUBLISHED, $published);
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return true;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * Set url
     *
     * @param string $previewUrl
     * @return $this
     */
    public function setPreviewUrl($previewUrl)
    {
        return $this->setData(self::PREVIEW_URL, $previewUrl);
    }

    /**
     * Get preview url
     *
     * @return string
     */
    public function getPreviewUrl()
    {
        return $this->getData(self::PREVIEW_URL);
    }
}
