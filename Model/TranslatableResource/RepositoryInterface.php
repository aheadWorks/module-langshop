<?php
namespace Aheadworks\Langshop\Model\TranslatableResource;

interface RepositoryInterface
{
    /**
     * Get entity
     *
     * @param int $id
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function get(int $id);

    /**
     * Retrieve entities matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Save entity
     *
     * @param \Magento\Framework\Model\AbstractModel $entity
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function save(\Magento\Framework\Model\AbstractModel $entity);
}
