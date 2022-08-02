<?php
declare(strict_types=1);
namespace Aheadworks\Langshop\Model\Source;

class CsvFile
{
    /**#@+
     * When we read csv file every line seems like array
     * where 0-index have original phrase value
     * and 1 - translation
     */
    public const ORIGINAL_INDEX = 0;
    public const TRANSLATION_INDEX = 1;
    /**#@-*/
}
