<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Controller\Adminhtml\Callback;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use tr33m4n\OauthGmail\Exception\AccessTokenException;
use tr33m4n\OauthGmail\Model\Client\GetClient;
use tr33m4n\OauthGmail\Model\SaveAccessToken;

class Authenticate extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    const ADMIN_RESOURCE = 'tr33m4n_OauthGmail::oauth';

    /**
     * {@inheritdoc}
     *
     * @var string[]
     */
    protected $_publicActions = [
        'authenticate'
    ];

    private GetClient $getClient;

    private SaveAccessToken $saveAccessToken;

    /**
     * Authenticate constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \tr33m4n\OauthGmail\Model\Client\GetClient $getClient
     * @param \tr33m4n\OauthGmail\Model\SaveAccessToken  $saveAccessToken
     */
    public function __construct(
        Context $context,
        GetClient $getClient,
        SaveAccessToken $saveAccessToken
    ) {
        $this->getClient = $getClient;
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
        $credentials = $this->getClient->execute()
            ->fetchAccessTokenWithAuthCode($this->getRequest()->getParam('code'));

        try {
            $this->saveAccessToken->execute($credentials);
        } catch (AccessTokenException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
        }

        $this->messageManager->addSuccessMessage((string) __('Successfully authenticated with Google!'));

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
