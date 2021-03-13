<?php

namespace tr33m4n\GoogleOauthMail\Controller\Adminhtml\Callback;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Mail\Template\TransportBuilder;

/**
 * Class Test
 *
 * @package tr33m4n\GoogleOauthMail\Controller\Adminhtml\Callback
 */
class Test extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'tr33m4n_GoogleOauthMail::oauth';

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * Test constructor.
     *
     * @param \Magento\Backend\App\Action\Context               $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder
    ) {
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
        $this->transportBuilder->setTemplateIdentifier('google_oauth_mail_test');
        $this->transportBuilder->setFromByScope('general');
        $this->transportBuilder->addTo('tr33m4n@googlemail.com', 'Daniel Doyle');

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $this->messageManager->addSuccessMessage(
            __('Successfully sent a test email to the address associated with this account!')
        );

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
