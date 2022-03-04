<?php
namespace Aheadworks\Langshop\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface LocaleInterface extends ExtensibleDataInterface
{
    const LOCALE = 'locale';
    const NAME = 'name';
    const PRIMARY = 'primary';
    const PUBLISHED = 'published';
    const URL = 'url';
    const PREVIEW_URL = 'preview_url';

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set locale
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get primary
     *
     * @return bool
     */
    public function getPrimary();

    /**
     * Set primary
     *
     * @param bool $primary
     * @return $this
     */
    public function setPrimary($primary);

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished();

    /**
     * Set published
     *
     * @param bool $published
     * @return $this
     */
    public function setPublished($published);

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Get preview url
     *
     * @return string
     */
    public function getPreviewUrl();

    /**
     * Set url
     *
     * @param string $previewUrl
     * @return $this
     */
    public function setPreviewUrl($previewUrl);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Langshop\Api\Data\LocaleExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Langshop\Api\Data\LocaleExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Langshop\Api\Data\LocaleExtensionInterface $extensionAttributes
    );
}
