<?php
declare(strict_types=1);

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
    private string $icon;

    /**
     * @var string
     */
    private string $viewType;

    /**
     * @var string
     */
    private string $defaultLocale;

    /**
     * @var Field[]
     */
    private array $fields;

    /**
     * @param FieldCollector $fieldCollector
     * @param string $resourceType
     * @param string $label
     * @param string $description
     * @param string $icon
     * @param string $viewType
     * @param string $defaultLocale
     */
    public function __construct(
        FieldCollector $fieldCollector,
        string $resourceType = '',
        string $label = '',
        string $description = '',
        string $icon = '',
        string $viewType = '',
        string $defaultLocale = ''
    ) {
        $this->fieldCollector = $fieldCollector;
        $this->resourceType = $resourceType;
        $this->label = $label;
        $this->description = $description;
        $this->icon = $icon;
        $this->viewType = $viewType;
        $this->defaultLocale = $defaultLocale;
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
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
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
     * Get default locale
     *
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
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
