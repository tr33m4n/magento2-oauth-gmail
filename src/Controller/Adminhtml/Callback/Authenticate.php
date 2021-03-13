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

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\SaveAccessToken
     */
    private $saveAccessToken;

    /**
     * Authenticate constructor.
     *
     * @param \Magento\Backend\App\Action\Context            $context
     * @param \tr33m4n\GoogleOauthMail\Model\GetGoogleClient $getGoogleClient
     * @param \tr33m4n\GoogleOauthMail\Model\SaveAccessToken $saveAccessToken
     */
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
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \tr33m4n\GoogleOauthMail\Exception\AccessTokenException
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute() : ResponseInterface
    {
        $client = $this->getGoogleClient->execute();
        $credentials = $client->fetchAccessTokenWithAuthCode($this->getRequest()->getParam('code'));

        // TODO: Refactor and handle credential errors properly
        if (array_key_exists('error', $credentials)) {
            $this->messageManager->addErrorMessage(__('Something went wrong: %1', json_encode($credentials)));

            return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
        }

        $this->saveAccessToken->execute($client->getAccessToken());
        $this->messageManager->addSuccessMessage(__('Successfully authenticated with Google!'));

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
