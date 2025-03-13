<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Magento\Framework\Api\DataObjectHelper;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Api\Data\TokenInterfaceFactory;
use tr33m4n\OauthGmail\Model\ResourceModel\Token as TokenResource;

class SaveAccessToken
{
    /**
     * SaveAccessToken constructor.
     */
    public function __construct(
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly ValidateAccessToken $validateAccessToken,
        private readonly TokenInterfaceFactory $tokenFactory,
        private readonly TokenResource $tokenResource
    ) {
    }

    /**
     * Save access token
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \tr33m4n\OauthGmail\Exception\AccessTokenException
     * @param array<string, mixed> $accessToken
     */
    public function execute(array $accessToken): void
    {
        $this->validateAccessToken->execute($accessToken);

        /** @var \tr33m4n\OauthGmail\Api\Data\TokenInterface $token */
        $token = $this->tokenFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $token,
            $accessToken,
            TokenInterface::class
        );

        /** @var \Magento\Framework\Model\AbstractModel $token */
        $this->tokenResource->save($token);
    }
}
