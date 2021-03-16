<?php

namespace tr33m4n\OauthGmail\Controller\Adminhtml\Callback;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use tr33m4n\OauthGmail\Exception\AccessTokenException;
use tr33m4n\OauthGmail\Model\GetGoogleClient;
use tr33m4n\OauthGmail\Model\SaveAccessToken;

/**
 * Class Authenticate
 *
 * @package tr33m4n\OauthGmail\Controller\Adminhtml\Callback
 */
class Authenticate extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'tr33m4n_OauthGmail::oauth';

    /**
     * @inheritDoc
     */
    protected $_publicActions = [
        'authenticate'
    ];

    /**
     * @var \tr33m4n\OauthGmail\Model\GetGoogleClient
     */
    private $getGoogleClient;

    /**
     * @var \tr33m4n\OauthGmail\Model\SaveAccessToken
     */
    private $saveAccessToken;

    /**
     * Authenticate constructor.
     *
     * @param \Magento\Backend\App\Action\Context            $context
     * @param \tr33m4n\OauthGmail\Model\GetGoogleClient $getGoogleClient
     * @param \tr33m4n\OauthGmail\Model\SaveAccessToken $saveAccessToken
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
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute() : ResponseInterface
    {
        $credentials = $this->getGoogleClient->execute()
            ->fetchAccessTokenWithAuthCode($this->getRequest()->getParam('code'));

        try {
            $this->saveAccessToken->execute($credentials);
        } catch (AccessTokenException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
        }

        $this->messageManager->addSuccessMessage(__('Successfully authenticated with Google!'));

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
