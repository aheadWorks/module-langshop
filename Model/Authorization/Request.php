<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Authorization;

use Magento\Framework\Webapi\Request as WebapiRequest;

class Request extends WebapiRequest
{
    /**
     * Added postprocessing of authorization header
     *
     * @param string $header
     * @param mixed|null $default
     * @return bool|string
     */
    public function getHeader($header, $default = false)
    {
        $headerValue = parent::getHeader($header, $default);

        if ($header === 'Authorization') {
            $headerValue = stristr($headerValue, 'bearer') ?: '';
        }

        return $headerValue;
    }
}
