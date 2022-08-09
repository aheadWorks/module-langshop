<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Api;

use Aheadworks\Langshop\Api\Data\StatusInterface;
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
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function save(StatusInterface $status): void;

    /**
     * Mass save
     *
     * @param \Aheadworks\Langshop\Api\Data\StatusInterface[] $statuses
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function massSave(array $statuses): void;

    /**
     * Deletes the status
     *
     * @param \Aheadworks\Langshop\Api\Data\StatusInterface $status
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function delete(StatusInterface $status): void;
}
