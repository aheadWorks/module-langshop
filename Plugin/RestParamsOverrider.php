<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin;

use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Magento\Webapi\Controller\Rest\ParamsOverrider;

class RestParamsOverrider
{
    /**
     * The body overriding does not recognize arrays as it should,
     * so needs to be disabled for translatable resources.
     *
     * @param ParamsOverrider $paramsOverrider
     * @param array $urlPathParams
     * @param array $requestBodyParams
     * @param string $serviceClassName
     * @param string $serviceMethodName
     * @return array
     */
    public function beforeOverrideRequestBodyIdWithPathParam(
        ParamsOverrider $paramsOverrider,
        array $urlPathParams,
        array $requestBodyParams,
        string $serviceClassName,
        string $serviceMethodName
    ): array {
        if ($serviceClassName == TranslatableResourceManagementInterface::class) {
            $urlPathParams = [];
        }

        return [$urlPathParams, $requestBodyParams, $serviceClassName, $serviceMethodName];
    }
}
