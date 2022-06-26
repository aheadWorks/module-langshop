<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema\TranslatableResource;

use Aheadworks\Langshop\Api\Data\Schema\TranslatableResource\FieldInterface;
use Magento\Framework\DataObject;

class Field extends DataObject implements FieldInterface
{
    /**
     * @inheritDoc
     */
    public function setKey(string $key): FieldInterface
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return  $this->getData(self::KEY);
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): FieldInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type): FieldInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setIsTranslatable(bool $isTranslatable): FieldInterface
    {
        return $this->setData(self::IS_TRANSLATABLE, $isTranslatable);
    }

    /**
     * @inheritDoc
     */
    public function isTranslatable(): bool
    {
        return $this->getData(self::IS_TRANSLATABLE);
    }

    /**
     * @inheritDoc
     */
    public function setIsTitle(bool $isTitle): FieldInterface
    {
        return $this->setData(self::IS_TITLE, $isTitle);
    }

    /**
     * @inheritDoc
     */
    public function isTitle(): bool
    {
        return $this->getData(self::IS_TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setFilter(string $filter): FieldInterface
    {
        return $this->setData(self::FILTER, $filter);
    }

    /**
     * @inheritDoc
     */
    public function getFilter(): string
    {
        return $this->getData(self::FILTER);
    }

    /**
     * @inheritDoc
     */
    public function setFilterOptions(array $filterOptions): FieldInterface
    {
        return $this->setData(self::FILTER_OPTIONS, $filterOptions);
    }

    /**
     * @inheritDoc
     */
    public function getFilterOptions(): array
    {
        return $this->getData(self::FILTER_OPTIONS);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder(int $sortOrder): FieldInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder(): int
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setVisibleOn(array $areas): FieldInterface
    {
        return $this->setData(self::VISIBLE_ON, $areas);
    }

    /**
     * @inheritDoc
     */
    public function getVisibleOn(): array
    {
        return $this->getData(self::VISIBLE_ON);
    }
}
