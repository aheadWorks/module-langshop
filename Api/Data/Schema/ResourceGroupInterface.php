<?php
namespace Aheadworks\Langshop\Api\Data\Schema;

interface ResourceGroupInterface
{
    const ID = 'id';
    const TITLE = 'title';
    const SORT_ORDER = 'sortOrder';
    const RESOURCES = 'resources';

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
     * @param array $resources
     * @return $this
     */
    public function setResources($resources);

    /**
     * Get resources
     *
     * @return array
     */
    public function getResources();
}
