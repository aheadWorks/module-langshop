<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Request;

use Magento\Framework\Exception\LocalizedException;

class Uninstall
{
    /**
     * @var Webhook
     */
    private Webhook $webhook;

    /**
     * @param Webhook $webhook
     */
    public function __construct(
        Webhook $webhook
    ) {
        $this->webhook = $webhook;
    }

    /**
     * Retrieves URL for the uninstall webhook
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->webhook->getUrl();
    }

    /**
     * Retrieves parameters for the uninstall webhook
     *
     * @return array
     * @throws LocalizedException
     */
    public function getParams(): array
    {
        return array_merge($this->webhook->getParams(), [
            'topic' => 'connector/uninstall',
            'payload' => []
        ]);
    }
}
