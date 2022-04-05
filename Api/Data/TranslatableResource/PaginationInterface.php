<?php
namespace Aheadworks\Langshop\Api\Data\TranslatableResource;

interface PaginationInterface
{
    const PAGE = 'page';
    const PAGE_SIZE = 'pageSize';
    const TOTAL_PAGES = 'totalPages';
    const TOTAL_ITEMS = 'totalItems';

    /**
     * Set page
     *
     * @param int $page
     * @return $this
     */
    public function setPage($page);

    /**
     * Get page
     *
     * @return int
     */
    public function getPage();

    /**
     * Set page size
     *
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize);

    /**
     * Get page size
     *
     * @return int
     */
    public function getPageSize();

    /**
     * Set total pages
     *
     * @param int $totalPages
     * @return $this
     */
    public function setTotalPages($totalPages);

    /**
     * Get total pages
     *
     * @return int
     */
    public function getTotalPages();

    /**
     * Set total items
     *
     * @param int $totalItems
     * @return $this
     */
    public function setTotalItems($totalItems);

    /**
     * Get total items
     *
     * @return int
     */
    public function getTotalItems();
}
