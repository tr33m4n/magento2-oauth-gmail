<?php

namespace tr33m4n\GoogleOauthMail\Controller\Adminhtml\Callback;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use tr33m4n\GoogleOauthMail\Model\GetGoogleClient;
use tr33m4n\GoogleOauthMail\Model\SaveAccessToken;

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
     * @inheritDoc
     */
    protected $_publicActions = [
        'authenticate'
    ];

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\GetGoogleClient
     */
    private $getGoogleClient;

    private $saveAccessToken;

    public function __construct(
        Context $context,
        GetGoogleClient $getGoogleClient,
        SaveAccessToken $saveAccessToken
    ) {
        $this->getGoogleClient = $getGoogleClient;
        $this->saveAccessToken = $saveAccessToken;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Google\Exception
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute() : ResponseInterface
    {
        $client = $this->getGoogleClient->execute();
        $client->fetchAccessTokenWithAuthCode($this->getRequest()->getParam('code'));

        $this->saveAccessToken->execute($client->getAccessToken());

        $this->messageManager->addSuccessMessage(__('Successfully authenticated with Google!'));

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
