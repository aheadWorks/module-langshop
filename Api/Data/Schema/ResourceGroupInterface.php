<?php
namespace Aheadworks\Langshop\Api\Data\Schema;

interface ResourceGroupInterface
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const SORT_ORDER = 'sortOrder';
    public const RESOURCES = 'resources';

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder();

    /**
     * Set resources
     *
     * @param \Aheadworks\Langshop\Api\Data\Schema\ResourceGroup\ResourceInterface[] $resources
     * @return $this
     */
    public function setResources($resources);

    /**
     * Get resources
     *
     * @return \Aheadworks\Langshop\Api\Data\Schema\ResourceGroup\ResourceInterface[]
     */
    public function getResources();
}
