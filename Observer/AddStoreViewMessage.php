<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\Store;

class AddStoreViewMessage implements ObserverInterface
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var MessageManagerInterface
     */
    private MessageManagerInterface $messageManager;

    /**
     * @param UrlInterface $urlBuilder
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        UrlInterface $urlBuilder,
        MessageManagerInterface $messageManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->messageManager = $messageManager;
    }

    /**
     * Adds an admin message once new store view added
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var Store $store */
        $store = $observer->getData('data_object');

        if ($store->isObjectNew()) {
            $message = __(
                'Specify the store view in <a href="%url">Langshop configuration</a> to translate it.',
                [
                    'url' => $this->urlBuilder->getUrl(
                        'adminhtml/system_config/edit',
                        ['section' => 'aw_ls']
                    )
                ]
            );

            $this->messageManager->addComplexNoticeMessage(
                'aw_ls_admin_message',
                ['message' => $message->render()]
            );
        }
    }
}
