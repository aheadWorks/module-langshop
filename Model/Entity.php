<?php
namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Field\Collector as FieldCollector;
use Magento\Framework\Exception\LocalizedException;

class Entity
{
    /**
     * @var FieldCollector
     */
    private FieldCollector $fieldCollector;

    /**
     * @var string
     */
    private string $resourceType;

    /**
     * @var string
     */
    private string $label;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var string
     */
    private string $viewType;

    /**
     * @var Field[]
     */
    private array $fields;

    /**
     * @param FieldCollector $fieldCollector
     * @param string $resourceType
     * @param string $label
     * @param string $description
     * @param string $viewType
     */
    public function __construct(
        FieldCollector $fieldCollector,
        string $resourceType = '',
        string $label = '',
        string $description = '',
        string $viewType = ''
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
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get view type
     *
     * @return string
     */
    public function getViewType(): string
    {
        return $this->viewType;
    }

    /**
     * Get fields
     *
     * @return Field[]
     * @throws LocalizedException
     */
    public function getFields(): array
    {
        if (empty($this->fields)) {
            $this->fields = $this->fieldCollector->collect();
        }

        return $this->fields;
    }
}
