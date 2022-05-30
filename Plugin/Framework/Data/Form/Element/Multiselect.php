<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Plugin\Framework\Data\Form\Element;

use Magento\Framework\Data\Form\Element\Multiselect as MultiselectElement;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class Multiselect
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var SecureHtmlRenderer
     */
    private SecureHtmlRenderer $secureHtmlRenderer;

    /**
     * @param SerializerInterface $serializer
     * @param SecureHtmlRenderer $secureHtmlRenderer
     */
    public function __construct(
        SerializerInterface $serializer,
        SecureHtmlRenderer $secureHtmlRenderer
    ) {
        $this->serializer = $serializer;
        $this->secureHtmlRenderer = $secureHtmlRenderer;
    }

    /**
     * Post-processing of select HTML and disables options
     *
     * @param MultiselectElement $multiselect
     * @param string $elementHtml
     * @return string
     */
    public function afterGetElementHtml(
        MultiselectElement $multiselect,
        string $elementHtml
    ): string {
        $disabledOptions = $this->getDisabledOptions(
            $multiselect->getData('values') ?? []
        );

        if ($disabledOptions) {
            $jsLayout = [
                '#' . $multiselect->getHtmlId() => [
                    'Aheadworks_Langshop/js/disable-options' => $disabledOptions
                ]
            ];

            $elementHtml .= $this->secureHtmlRenderer->renderTag(
                'script',
                ['type' => 'text/x-magento-init'],
                $this->serializer->serialize($jsLayout),
                false
            );
        }

        return $elementHtml;
    }

    /**
     * Retrieves values of options need to be disabled
     *
     * @param array $options
     * @return string[]
     */
    private function getDisabledOptions(array $options): array
    {
        $disabledOptions = [];

        foreach ($options as $option) {
            if (is_array($option['value'])) {
                $disabledOptions += $this->getDisabledOptions($option['value']);
            } elseif (isset($option['disabled']) && $option['disabled']) {
                $disabledOptions[] = $option['value'];
            }
        }

        return $disabledOptions;
    }
}
