<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Saas;

use Magento\Framework\HTTP\Client\Curl;

class CurlResponseHandler
{
    public const MESSAGES = [
        403 => 'This feature is not available in your current plan.'
    ];

    /**
     * Retrieve response
     *
     * @param Curl $response
     * @return array
     */
    public function prepareResponse(Curl $response): array
    {
        $result = [];

        if ($response->getStatus() > 400) {
            $result = [
                'code' => $response->getStatus(),
                'message' => __($this->getResponseMessage($response->getStatus()))
            ];
        }

        return $result;
    }

    /**
     * Retrieve error message
     *
     * @param int $status
     * @return string
     */
    private function getResponseMessage(int $status)
    {
        if (isset(self::MESSAGES[$status])) {
            return self::MESSAGES[$status];
        }

        return 'Something went wrong.';
    }
}
