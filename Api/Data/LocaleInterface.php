<?php
namespace Aheadworks\Langshop\Api\Data;

interface LocaleInterface
{
    public const LOCALE = 'locale';
    public const NAME = 'name';
    public const PRIMARY = 'primary';
    public const PUBLISHED = 'published';
    public const URL = 'url';
    public const PREVIEW_URL = 'preview_url';

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
}
