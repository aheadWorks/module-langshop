<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Entity\AutoTranslation;

use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\Langshop\Model\Config\AutoUpdateTranslation;
use Aheadworks\Langshop\Model\Locale\Scope\Record\Repository as LocaleScopeRepository;
use Aheadworks\Langshop\Model\Entity\Pool as EntityPool;
use Aheadworks\Langshop\Model\Entity\Converter as EntityConverter;
use Aheadworks\Langshop\Model\Status\StatusChecker;
use Aheadworks\Langshop\Model\Saas\TranslateAction;
use Aheadworks\Langshop\Model\Saas\CurlResponseHandler;

class Processor
{
    /**
     * @param Logger $logger
     * @param AutoUpdateTranslation $autoUpdateTranslation
     * @param LocaleScopeRepository $localeScopeRepository
     * @param EntityPool $entityPool
     * @param EntityConverter $entityConverter
     * @param StatusChecker $statusChecker
     * @param TranslateAction $translateAction
     * @param CurlResponseHandler $curlResponseHandler
     */
    public function __construct(
        private readonly Logger $logger,
        private readonly AutoUpdateTranslation $autoUpdateTranslation,
        private readonly LocaleScopeRepository $localeScopeRepository,
        private readonly EntityPool $entityPool,
        private readonly EntityConverter $entityConverter,
        private readonly StatusChecker $statusChecker,
        private readonly TranslateAction $translateAction,
        private readonly CurlResponseHandler $curlResponseHandler
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
        if (!$this->canProceed($resource, $resourceType)) {
            return;
        }

        $translatableFields = $this->getTranslatedFields($resourceType);
        $modifiedData = array_intersect_key($resource->getData(), array_flip($translatableFields));
        $originalData = array_intersect_key($resource->getOrigData(), array_flip($translatableFields));

        if (($originalData === $modifiedData)
            || $this->statusChecker->isProcessing($resourceType, $resource->getEntityId())
        ) {
            return;
        }

        $response = $this->translateAction->makeRequest($resourceType, (int)$resource->getEntityId());
        if ($response->getStatus() > 400) {
            $response = $this->curlResponseHandler->prepareResponse($response);
            if (isset($response['message'])) {
                $this->logger->warning($response['message']);
            }
        } else {
            $this->translateAction->setProcessingStatus($resourceType, (int)$resource->getEntityId());
        }
    }

    /**
     * Can proceed
     *
     * @param AbstractModel $resource
     * @param string $resourceType
     * @return bool
     * @throws NoSuchEntityException
     */
    private function canProceed(AbstractModel $resource, string $resourceType): bool
    {
        if (!$this->autoUpdateTranslation->isEnabled()) {
            return false;
        }

        $defaultLocale = $this->localeScopeRepository->getPrimary($resourceType);
        if ($resource->getData('store_id') != $defaultLocale->getScopeId()) {
            return false;
        }

        return true;
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
