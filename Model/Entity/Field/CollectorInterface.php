<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\Field;

use Aheadworks\Langshop\Model\Entity\Field;

interface CollectorInterface
{
    /**
     * Collect entity fields
     *
     * @param Field[] $fields
     * @return Field[]
     */
    public function collect(array $fields = []): array;
}
