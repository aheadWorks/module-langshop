<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Exception;

interface StatusManagementInterface
{
    /**
     * Retrieves statuses by resource type and id
     *
     * @param string $resourceType
     * @param int $resourceId
     * @return \Aheadworks\Langshop\Api\Data\StatusInterface[]
     */
    public function getList(string $resourceType, int $resourceId): array;

    /**
     * Saves the status
     *
     * @param \Aheadworks\Langshop\Api\Data\StatusInterface $status
     * @throws Exception
     */
    public function save(StatusInterface $status): void;

    /**
     * Deletes the status
     *
     * @param \Aheadworks\Langshop\Api\Data\StatusInterface $status
     * @throws Exception
     */
    public function delete(StatusInterface $status): void;
}
