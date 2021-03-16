<?php

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

/**
 * Class Test
 *
 * @package tr33m4n\OauthGmail\Controller\Adminhtml\Email
 */
class Test extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'tr33m4n_OauthGmail::oauth';

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    private $authSession;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * Test constructor.
     *
     * @param \Magento\Backend\App\Action\Context               $context
     * @param \Magento\Backend\Model\Auth\Session               $authSession
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        AuthSession $authSession,
        TransportBuilder $transportBuilder
    ) {
        $this->authSession = $authSession;
        $this->transportBuilder = $transportBuilder;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute() : ResponseInterface
    {
        $adminUser = $this->authSession->getUser();

        $this->transportBuilder->setTemplateIdentifier('google_oauth_mail_test')
            ->setFromByScope('general')
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
