<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Setup\Patch\Data;

use Aheadworks\Langshop\Model\Saas\CurlSender;
use Aheadworks\Langshop\Model\Saas\Request\Uninstall as UninstallRequest;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class UninstallWebhook implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var CurlSender
     */
    private CurlSender $curlSender;

    /**
     * @var UninstallRequest
     */
    private UninstallRequest $uninstallRequest;

    /**
     * @param CurlSender $curlSender
     * @param UninstallRequest $uninstallRequest
     */
    public function __construct(
        CurlSender $curlSender,
        UninstallRequest $uninstallRequest
    ) {
        $this->curlSender = $curlSender;
        $this->uninstallRequest = $uninstallRequest;
    }

    /**
     * Runs code inside the patch
     *
     * @return $this
     */
    public function apply()
    {
        return $this;
    }

    /**
     * Sends uninstall webhook to Langshop
     *
     * @throws LocalizedException
     */
    public function revert()
    {
        $this->curlSender->post(
            $this->uninstallRequest->getUrl(),
            $this->uninstallRequest->getParams()
        );
    }

    /**
     * Gets dependencies for the patch
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Gets aliases for the patch
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }
}
