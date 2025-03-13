<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Magento\Framework\Serialize\SerializerInterface;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Exception\AccessTokenException;

class ValidateAccessToken
{
    private const REQUIRED_FIELDS = [
        TokenInterface::KEY_ACCESS_TOKEN,
        TokenInterface::KEY_EXPIRES_IN,
        TokenInterface::KEY_SCOPE,
        TokenInterface::KEY_CREATED,
        TokenInterface::KEY_REFRESH_TOKEN,
        TokenInterface::KEY_TOKEN_TYPE
    ];

    private const ERROR_KEY = 'error';

    /**
     * ValidateCredentials constructor.
     */
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Validate access token
     *
     * @throws \tr33m4n\OauthGmail\Exception\AccessTokenException
     * @param array<string, mixed> $accessToken
     */
    public function execute(array $accessToken): void
    {
        if (array_key_exists(self::ERROR_KEY, $accessToken)) {
            throw new AccessTokenException(
                __(
                    'An error has occurred whilst fetching the access token: %1',
                    $this->serializer->serialize($accessToken)
                )
            );
        }

        $hasKeyCount = count($accessToken) === count(self::REQUIRED_FIELDS);
        $hasCorrectKeys = array_diff_key(array_flip(self::REQUIRED_FIELDS), $accessToken) === [];

        if (!$hasKeyCount || !$hasCorrectKeys) {
            throw new AccessTokenException(__('Access token is invalid!'));
        }
    }
}
