<?php
namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Field\Collector as FieldCollector;
use Magento\Framework\Exception\LocalizedException;

class Entity
{
    /**
     * @var string
     */
    private $resourceType;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $viewType;

    /**
     * @var FieldCollector
     */
    private $fieldCollector;

    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @param FieldCollector $fieldCollector
     * @param string $resourceType
     * @param string $label
     * @param string $description
     * @param string $viewType
     */
    public function __construct(
        FieldCollector $fieldCollector,
        $resourceType = '',
        $label = '',
        $description = '',
        $viewType = ''
    ) {
        $this->fieldCollector = $fieldCollector;
        $this->resourceType = $resourceType;
        $this->label = $label;
        $this->description = $description;
        $this->viewType = $viewType;
    }

    /**
     * Get resource type
     *
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get view type
     *
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    /**
     * Get fields
     *
     * @return Field[]
     * @throws LocalizedException
     */
    public function getFields()
    {
        if (empty($this->fields)) {
            $this->fields = $this->fieldCollector->collect();
        }

        return $this->fields;
    }
}
