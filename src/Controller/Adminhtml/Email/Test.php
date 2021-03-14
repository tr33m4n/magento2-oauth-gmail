<?php

namespace tr33m4n\GoogleOauthMail\Controller\Adminhtml\Email;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Mail\Template\TransportBuilder;

/**
 * Class Test
 *
 * @package tr33m4n\GoogleOauthMail\Controller\Adminhtml\Email
 */
class Test extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'tr33m4n_GoogleOauthMail::oauth';

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
        $this->transportBuilder->setTemplateIdentifier('google_oauth_mail_test');
        $this->transportBuilder->setFromByScope('general');
        $this->transportBuilder->addTo($adminUser->getEmail(), $adminUser->getName());

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $this->messageManager->addSuccessMessage(
            __('Successfully sent a test email to the address associated with this account!')
        );

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
