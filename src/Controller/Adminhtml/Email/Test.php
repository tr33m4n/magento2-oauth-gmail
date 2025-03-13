<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Controller\Adminhtml\Email;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;
use tr33m4n\OauthGmail\Model\Config\Provider;

class Test extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    public const ADMIN_RESOURCE = 'tr33m4n_OauthGmail::oauth';

    /**
     * Test constructor.
     */
    public function __construct(
        private readonly Provider $configProvider,
        private readonly AuthSession $authSession,
        private readonly TransportBuilder $transportBuilder,
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): ResponseInterface
    {
        if (!$this->configProvider->hasAuthCredentials()) {
            $this->messageManager->addErrorMessage(__('Auth credentials have not been configured'));

            return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
        }

        $adminUser = $this->authSession->getUser();

        $this->transportBuilder->setTemplateIdentifier('google_oauth_mail_test')
            ->setFromByScope($this->configProvider->getTestScope() ?? 'general')
            ->addTo($adminUser->getEmail(), $adminUser->getName())
            ->setTemplateOptions([
                'area' => FrontNameResolver::AREA_CODE,
                'store' => Store::DEFAULT_STORE_ID
            ])
            ->setTemplateVars([]);

        $transport = $this->transportBuilder->getTransport();

        try {
            $transport->sendMessage();

            $this->messageManager->addSuccessMessage(
                __('Successfully sent a test email to the address associated with this account!')
            );
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage(__('Test email failed to send: %1', $exception->getMessage()));
        }

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
