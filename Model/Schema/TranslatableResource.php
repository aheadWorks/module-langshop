<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Schema;

use Aheadworks\Langshop\Api\Data\Schema\ResourceInterface;
use Magento\Framework\DataObject;

class TranslatableResource extends DataObject implements ResourceInterface
{
    /**
     * @inheritDoc
     */
    public function setResource($resource)
    {
        return $this->setData(self::RESOURCE, $resource);
    }

    /**
     * @inheritDoc
     */
    public function getResource()
    {
        return $this->getData(self::RESOURCE);
    }

    /**
     * @inheritDoc
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setFields($fields)
    {
        return $this->setData(self::FIELDS, $fields);
    }

    /**
     * @inheritDoc
     */
    public function getFields()
    {
        return $this->getData(self::FIELDS);
    }

    /**
     * @inheritDoc
     */
    public function setSorting(array $sorting)
    {
        return $this->setData(self::SORTING, $sorting);
    }

    /**
     * @inheritDoc
     */
    public function getSorting()
    {
        return $this->getData(self::SORTING);
    }

    /**
     * @inheritDoc
     */
    public function setViewType($viewType)
    {
        return $this->setData(self::VIEW_TYPE, $viewType);
    }

    /**
     * @inheritDoc
     */
    public function getViewType()
    {
        return $this->getData(self::VIEW_TYPE);
    }
}
