<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Api;

interface SaasManagementInterface
{
    /**
     * Save key
     *
     * @param string $publicKey
     * @return bool
     */
    public function saveKey(string $publicKey): bool;
}
