<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Controller\Adminhtml\Callback;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use tr33m4n\OauthGmail\Api\GmailClientInterface;
use tr33m4n\OauthGmail\Api\TokenRepositoryInterface;
use tr33m4n\OauthGmail\Exception\AccessTokenException;
use tr33m4n\OauthGmail\Model\TokenFactory;

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
        private readonly GmailClientInterface $gmailClient,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly TokenFactory $tokenFactory,
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(): ResponseInterface
    {
        try {
            $code = $this->getRequest()->getParam('code');
            if (!is_scalar($code)) {
                $code = '';
            }

            $this->tokenRepository->save(
                $this->tokenFactory->create(
                    $this->gmailClient->getAccessTokenWithAuthCode((string) $code)
                )
            );
        } catch (AccessTokenException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
        }

        $this->messageManager->addSuccessMessage((string) __('Successfully authenticated with Google!'));

        return $this->_redirect('adminhtml/system_config/edit', ['section' => 'system']);
    }
}
