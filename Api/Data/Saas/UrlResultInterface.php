<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api\Data\Saas;

interface UrlResultInterface
{
    /**
     * Set URL
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): UrlResultInterface;

    /**
     * Get URL
     *
     * @return string|null
     */
    public function getUrl(): ?string;
}
