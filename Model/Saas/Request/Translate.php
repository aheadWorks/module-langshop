<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas\Request;

use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;

class Translate
{
    /**
     * @var Random
     */
    private Random $random;

    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

    /**
     * @param Random $random
     * @param SaasConfig $saasConfig
     */
    public function __construct(
        Random $random,
        SaasConfig $saasConfig
    ) {
        $this->random = $random;
        $this->saasConfig = $saasConfig;
    }

    /**
     * Retrieves URL for the translate request
     *
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf('%swebhooks', $this->saasConfig->getDomain());
    }

    /**
     * Retrieves parameters for the translate request
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return array
     * @throws LocalizedException
     */
    public function getParams(string $resourceType, int $resourceId): array
    {
        return [
            'id' => $this->random->getRandomString(10),
            'key' => $this->saasConfig->getPublicKey(),
            'topic' => 'connector/translate',
            'payload' => [
                'resourceId' => sprintf('gid://%s/%d', $resourceType, $resourceId)
            ]
        ];
    }
}
