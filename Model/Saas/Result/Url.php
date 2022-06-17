<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Result;

use Aheadworks\Langshop\Api\Data\Saas\UrlResultInterface;

class Url implements UrlResultInterface
{
    /**
     * @var string|null
     */
    private ?string $url = null;

    /**
     * Set URL
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): UrlResultInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get URL
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
