<?php
namespace Aheadworks\Langshop\Model\Locale\Scope;

use Magento\Framework\Escaper as FrameworkEscaper;

class Escaper
{
    /**
     * @var FrameworkEscaper
     */
    private $escaper;

    /**
     * @param FrameworkEscaper $escaper
     */
    public function __construct(
        FrameworkEscaper $escaper
    ) {
        $this->escaper = $escaper;
    }

    /**
     * Retrieve sanitized name of the given scope
     *
     * @param string $scopeName
     *
     * @return string
     */
    public function getSanitizedName($scopeName)
    {
        $matches = [];
        preg_match('/\$[:]*{(.)*}/', $scopeName, $matches);
        if (count($matches) > 0) {
            $sanitizedName = $this->escaper->escapeHtml(
                $this->escaper->escapeJs($scopeName)
            );
        } else {
            $sanitizedName = $this->escaper->escapeHtml(
                $scopeName
            );
        }

        return $sanitizedName;
    }
}
