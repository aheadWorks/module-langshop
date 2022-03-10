<?php
namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Api\Data\LocaleInterface;
use Magento\Framework\Model\AbstractModel;

class Locale extends AbstractModel implements LocaleInterface
{
    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->getData(self::LOCALE);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrimary($primary)
    {
        return $this->setData(self::PRIMARY, $primary);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimary()
    {
        return $this->getData(self::PRIMARY);
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published)
    {
        return $this->setData(self::PUBLISHED, $published);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublished()
    {
        return $this->getData(self::PUBLISHED);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setPreviewUrl($previewUrl)
    {
        return $this->setData(self::PREVIEW_URL, $previewUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviewUrl()
    {
        return $this->getData(self::PREVIEW_URL);
    }
}
