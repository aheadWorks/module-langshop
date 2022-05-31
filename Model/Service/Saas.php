<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Model\Service;

use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterface;
use Aheadworks\Langshop\Api\Data\Saas\ConfirmationResultInterfaceFactory;
use Aheadworks\Langshop\Api\SaasManagementInterface;
use Aheadworks\Langshop\Model\Config\Saas as SaasConfig;

class Saas implements SaasManagementInterface
{
    /**
     * @var SaasConfig
     */
    private SaasConfig $saasConfig;

    /**
     * @var ConfirmationResultInterfaceFactory
     */
    private ConfirmationResultInterfaceFactory $resultFactory;

    /**
     * @param SaasConfig $saasConfig
     * @param ConfirmationResultInterfaceFactory $resultFactory
     */
    public function __construct(
        SaasConfig $saasConfig,
        ConfirmationResultInterfaceFactory $resultFactory
    ) {
        $this->saasConfig = $saasConfig;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Save public key
     *
     * @param string $publicKey
     * @return ConfirmationResultInterface
     */
    public function saveKey(string $publicKey): ConfirmationResultInterface
    {
        /** @var ConfirmationResultInterface $result */
        $result = $this->resultFactory->create();
        $this->saasConfig->savePublicKey($publicKey);

        return $result->setSuccess(true);
    }
}
