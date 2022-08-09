<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Request;

use Magento\Framework\Exception\LocalizedException;

class Translate
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
     * Retrieves URL for the translate webhook
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->webhook->getUrl();
    }

    /**
     * Retrieves parameters for the translate webhook
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return array
     * @throws LocalizedException
     */
    public function getParams(string $resourceType, int $resourceId): array
    {
        return array_merge($this->webhook->getParams(), [
            'topic' => 'connector/translate',
            'payload' => [
                'resourceId' => sprintf('gid://%s/%d', $resourceType, $resourceId)
            ]
        ]);
    }
}
