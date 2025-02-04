<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\AutoTranslation;

use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Aheadworks\Langshop\Model\Entity\Converter as EntityConverter;
use Aheadworks\Langshop\Model\Status\StatusChecker;
use Aheadworks\Langshop\Model\Saas\TranslateAction;
use Aheadworks\Langshop\Model\Saas\CurlResponseHandler;

class Processor
{
    /**
     * @param Logger $logger
     * @param EntityPool $entityPool
     * @param EntityConverter $entityConverter
     * @param StatusChecker $statusChecker
     * @param TranslateAction $translateAction
     * @param CurlResponseHandler $curlResponseHandler
     * @param Checker $checker
     */
    public function __construct(
        private readonly Logger $logger,
        private readonly EntityPool $entityPool,
        private readonly EntityConverter $entityConverter,
        private readonly StatusChecker $statusChecker,
        private readonly TranslateAction $translateAction,
        private readonly CurlResponseHandler $curlResponseHandler,
        private readonly Checker $checker
    ) {
    }

    /**
     * Force translate
     *
     * @param AbstractModel $resource
     * @param string $resourceType
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function forceTranslate(
        AbstractModel $resource,
        string $resourceType,
    ): void {
        if (!$this->checker->canAutoTranslate($resource, $resourceType)) {
            return;
        }

        $translatableFields = $this->getTranslatedFields($resourceType);
        $modifiedData = array_intersect_key($resource->getData(), array_flip($translatableFields));
        $originalData = array_intersect_key($resource->getOrigData() ?? [], array_flip($translatableFields));

        if (($originalData === $modifiedData)
            || $this->statusChecker->isProcessing($resourceType, $resource->getId())
        ) {
            return;
        }

        $response = $this->translateAction->makeRequest($resourceType, (int)$resource->getId());
        if ($response->getStatus() > 400) {
            $response = $this->curlResponseHandler->prepareResponse($response);
            if (isset($response['message'])) {
                $this->logger->warning($response['message']);
            }
        } else {
            $this->translateAction->setProcessingStatus($resourceType, (int)$resource->getId());
        }
    }

    /**
     * Get translated fields
     *
     * @param string $resourceType
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    private function getTranslatedFields(string $resourceType): array
    {
        $entity = $this->entityPool->getByType($resourceType);
        $schemaResource = $this->entityConverter->convert($entity);

        $translatableFields = [];
        foreach ($schemaResource->getFields() as $field) {
            if ($field->isTranslatable()) {
                $translatableFields[] = $field->getKey();
            }
        }

        return $translatableFields;
    }
}
