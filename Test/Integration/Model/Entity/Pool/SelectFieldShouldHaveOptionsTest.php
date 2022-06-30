<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Test\Integration\Model\Entity\Pool;

use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;

class SelectFieldShouldHaveOptionsTest extends TestCase
{
    /**
     * Attribute types to check
     */
    private const SELECT_FILTER_TYPES = [
        'select',
        'multiselect'
    ];

    /**
     * @var EntityPool|null
     */
    private ?EntityPool $entityPool;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = ObjectManager::getInstance();
        $this->entityPool = $objectManager->create(EntityPool::class);
    }

    /**
     * All select fields should have source model for options
     *
     * @throws LocalizedException
     */
    public function testSelectOptions(): void
    {
        $entities = $this->entityPool->getAll();

        foreach ($entities as $entity) {
            foreach ($entity->getFields() as $field) {
                if (in_array($field->getFilterType(), self::SELECT_FILTER_TYPES)) {
                    $this->assertNotNull($field->getFilterOptions());

                    if ($field->getFilterOptions()) {
                        $this->assertNotEmpty($field->getFilterOptions()->toOptionArray());
                    }
                }
            }
        }
    }
}
