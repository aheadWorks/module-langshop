<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface;
use Magento\Framework\DataObject;

class Field extends DataObject implements FieldInterface
{
    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): FieldInterface
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string
    {
        return  $this->getData(self::KEY);
    }

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): FieldInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): FieldInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set is translatable
     *
     * @param bool $isTranslatable
     * @return $this
     */
    public function setIsTranslatable(bool $isTranslatable): FieldInterface
    {
        return $this->setData(self::IS_TRANSLATABLE, $isTranslatable);
    }

    /**
     * Is translatable
     *
     * @return bool
     */
    public function isTranslatable(): bool
    {
        return $this->getData(self::IS_TRANSLATABLE);
    }

    /**
     * Set visible areas for field
     *
     * @param string[] $areas
     * @return Field
     */
    public function setVisibleOn(array $areas): FieldInterface
    {
        return $this->setData(self::VISIBLE_ON, $areas);
    }

    /**
     * Get visible areas for field
     *
     * @return string[]
     */
    public function getVisibleOn(): array
    {
        return $this->getData(self::VISIBLE_ON);
    }

    /**
     * Set filter
     *
     * @param string $filter
     * @return $this
     */
    public function setFilter(string $filter): FieldInterface
    {
        return $this->setData(self::FILTER, $filter);
    }

    /**
     * Get filter
     *
     * @return string
     */
    public function getFilter(): string
    {
        return $this->getData(self::FILTER);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): FieldInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->getData(self::SORT_ORDER);
    }
}
