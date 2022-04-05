<?php
namespace Aheadworks\Langshop\Model\TranslatableResource;

use Aheadworks\Langshop\Api\Data\TranslatableResource\PaginationInterface;
use Magento\Framework\Model\AbstractModel;

class Pagination extends AbstractModel implements PaginationInterface
{
    /**
     * @inheritDoc
     */
    public function setPage($page)
    {
        return $this->setData(self::PAGE, $page);
    }

    /**
     * @inheritDoc
     */
    public function getPage()
    {
        return $this->getData(self::PAGE);
    }

    /**
     * @inheritDoc
     */
    public function setPageSize($pageSize)
    {
        return $this->setData(self::PAGE_SIZE, $pageSize);
    }

    /**
     * @inheritDoc
     */
    public function getPageSize()
    {
        return $this->getData(self::PAGE_SIZE);
    }

    /**
     * @inheritDoc
     */
    public function setTotalPages($totalPages)
    {
        return $this->setData(self::TOTAL_PAGES, $totalPages);
    }

    /**
     * @inheritDoc
     */
    public function getTotalPages()
    {
        return $this->getData(self::TOTAL_PAGES);
    }

    /**
     * @inheritDoc
     */
    public function setTotalItems($totalItems)
    {
        return $this->setData(self::TOTAL_ITEMS, $totalItems);
    }

    /**
     * @inheritDoc
     */
    public function getTotalItems()
    {
        return $this->getData(self::TOTAL_ITEMS);
    }
}
