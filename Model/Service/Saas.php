<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterfaceFactory;
use Aheadworks\Langshop\Api\Data\Saas\UrlResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\UrlResultInterfaceFactory;
use Aheadworks\Langshop\Api\SaasManagementInterface;
use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;
use Magento\Backend\Model\UrlInterface;

class Saas implements SaasManagementInterface
{
    /**
     * @var ConfirmationResultInterfaceFactory
     */
    private ConfirmationResultInterfaceFactory $confirmationResultFactory;

    /**
     * @var UrlResultInterfaceFactory
     */
    private UrlResultInterfaceFactory $urlResultFactory;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

    /**
     * @param ConfirmationResultInterfaceFactory $confirmationResultFactory
     * @param UrlResultInterfaceFactory $urlResultFactory
     * @param UrlInterface $urlBuilder
     * @param SaasConfig $saasConfig
     */
    public function __construct(
        ConfirmationResultInterfaceFactory $confirmationResultFactory,
        UrlResultInterfaceFactory $urlResultFactory,
        UrlInterface $urlBuilder,
        SaasConfig $saasConfig
    ) {
        $this->confirmationResultFactory = $confirmationResultFactory;
        $this->urlResultFactory = $urlResultFactory;
        $this->urlBuilder = $urlBuilder;
        $this->saasConfig = $saasConfig;
    }

    /**
     * Save public key
     *
     * @param string $publicKey
     * @return ConfirmationResultInterface
     */
    public function saveKey(string $publicKey): ConfirmationResultInterface
    {
        $this->saasConfig->savePublicKey($publicKey);

        return $this->confirmationResultFactory->create()
            ->setSuccess(true);
    }

    /**
     * Get admin Langshop URL
     *
     * @return UrlResultInterface
     */
    public function getLangshopUrl(): UrlResultInterface
    {
        $langshopUrl = $this->urlBuilder
            ->turnOffSecretKey()
            ->getUrl('langshop/page');

        return $this->urlResultFactory->create()
            ->setUrl($langshopUrl);
    }
}
