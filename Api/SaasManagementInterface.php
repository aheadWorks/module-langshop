<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\UrlResultInterface;

interface SaasManagementInterface
{
    /**
     * Save key
     *
     * @param string $publicKey
     * @return \Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface
     */
    public function saveKey(string $publicKey): ConfirmationResultInterface;

    /**
     * Get admin dashboard URL
     *
     * @return \Aheadworks\Langshop\Api\Data\Saas\UrlResultInterface
     */
    public function getDashboardUrl(): UrlResultInterface;
}
