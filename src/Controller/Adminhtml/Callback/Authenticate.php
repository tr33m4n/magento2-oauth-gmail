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
    public const ADMIN_RESOURCE = 'tr33m4n_OauthGmail::oauth';

    /**
     * {@inheritdoc}
     *
     * @var string[]
     */
    protected $_publicActions = [
        'authenticate'
    ];

    /**
     * Authenticate constructor.
     */
    public function __construct(
        private readonly GetClient $getClient,
        private readonly SaveAccessToken $saveAccessToken,
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Google\Exception
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute(): ResponseInterface
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
