<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\StatusInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;

interface StatusManagementInterface
{
    /**
     * Retrieves statuses by resource type and id
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Langshop\Api\Data\StatusInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

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
