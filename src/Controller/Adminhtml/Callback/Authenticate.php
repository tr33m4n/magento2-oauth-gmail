<?php

namespace tr33m4n\GoogleOauthMail\Controller\Adminhtml\Callback;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use tr33m4n\GoogleOauthMail\Model\GetGoogleClient;

/**
 * Class Authenticate
 *
 * @package tr33m4n\GoogleOauthMail\Controller\Adminhtml\Callback
 */
class Authenticate extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'tr33m4n_GoogleOauthMail::oauth';

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\GetGoogleClient
     */
    private $getGoogleClient;

    /**
     * Authenticate constructor.
     *
     * @param \Magento\Backend\App\Action\Context            $context
     * @param \tr33m4n\GoogleOauthMail\Model\GetGoogleClient $getGoogleClient
     */
    public function __construct(
        Context $context,
        GetGoogleClient $getGoogleClient
    ) {
        $this->getGoogleClient = $getGoogleClient;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Google\Exception
     * @return void
     */
    public function execute() : void
    {
        $this->getGoogleClient->execute()
            ->fetchAccessTokenWithAuthCode($this->getRequest()->getParam('code'));

        $this->messageManager->addSuccessMessage(__('Successfully authenticated with Google!'));

        $this->_forward('edit', 'system_config', ['section' => 'system']);
    }
}
