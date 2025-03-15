<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Magento\Framework\Api\DataObjectHelper;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Api\Data\TokenInterfaceFactory;

class TokenFactory
{
    /**
     * TokenFactory constructor.
     */
    public function __construct(
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly ValidateAccessToken $validateAccessToken,
        private readonly TokenInterfaceFactory $tokenFactory
    ) {
    }

    /**
     * Create token
     *
     * @param array<string, mixed> $tokenData
     * @throws \tr33m4n\OauthGmail\Exception\AccessTokenException
     */
    public function create(array $tokenData): TokenInterface
    {
        $this->validateAccessToken->execute($tokenData);

        /** @var \tr33m4n\OauthGmail\Api\Data\TokenInterface $token */
        $token = $this->tokenFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $token,
            $tokenData,
            TokenInterface::class
        );

        return $token;
    }
}
