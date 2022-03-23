<?php
namespace Aheadworks\Langshop\Model\Entity\Field\Collector;

use Aheadworks\Langshop\Model\Entity\Field;
use Aheadworks\Langshop\Model\Entity\Field\CollectorInterface;

class StaticAttribute implements CollectorInterface
{
    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @param Field[] $fields
     */
    public function __construct(
        array $fields = []
    ) {
        $this->fields = $fields;
    }

    /**
     * @inheritDoc
     */
    public function collect(array $fields = [])
    {
        foreach ($this->fields as $code => $field) {
            $fields[$code] = $field;
        }

        return $fields;
    }
}
