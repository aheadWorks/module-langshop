<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Webapi\Controller\Rest;

use Aheadworks\Langshop\Api\TranslatableResourceManagementInterface;
use Magento\Webapi\Controller\Rest\ParamsOverrider as WebapiParamsOverrider;

class ParamsOverrider
{
    /**
     * Disables body overriding for translatable resources
     *
     * @param WebapiParamsOverrider $paramsOverrider
     * @param array $urlPathParams
     * @param array $requestBodyParams
     * @param string $serviceClassName
     * @param string $serviceMethodName
     * @return array
     */
    public function beforeOverrideRequestBodyIdWithPathParam(
        WebapiParamsOverrider $paramsOverrider,
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
