<?php
namespace Aheadworks\Langshop\Model;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Field\Repository as FieldRepository;
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
     * @var FieldRepository
     */
    private $fieldRepository;

    /**
     * @param FieldRepository $fieldRepository
     * @param string $resourceType
     * @param string $label
     * @param string $description
     * @param string $viewType
     */
    public function __construct(
        FieldRepository $fieldRepository,
        $resourceType = '',
        $label = '',
        $description = '',
        $viewType = ''
    ) {
        $this->fieldRepository = $fieldRepository;
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
        return $this->fieldRepository->get($this->getResourceType());
    }
}
