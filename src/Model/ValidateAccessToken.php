<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Magento\Framework\Serialize\SerializerInterface;
use tr33m4n\GoogleOauthMail\Api\Data\TokenInterface;
use tr33m4n\GoogleOauthMail\Exception\AccessTokenException;

/**
 * Class ValidateAccessToken
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class ValidateAccessToken
{
    const REQUIRED_FIELDS = [
        TokenInterface::KEY_ACCESS_TOKEN,
        TokenInterface::KEY_EXPIRES_IN,
        TokenInterface::KEY_SCOPE,
        TokenInterface::KEY_CREATED,
        TokenInterface::KEY_REFRESH_TOKEN,
        TokenInterface::KEY_TOKEN_TYPE
    ];

    const ERROR_KEY = 'error';

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * ValidateCredentials constructor.
     *
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    /**
     * Validate access token
     *
     * @throws \tr33m4n\GoogleOauthMail\Exception\AccessTokenException
     * @param array $accessToken
     */
    public function execute(array $accessToken) : void
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
        $hasCorrectKeys = empty(array_diff_key(array_flip(self::REQUIRED_FIELDS), $accessToken));

        if (!$hasKeyCount || !$hasCorrectKeys) {
            throw new AccessTokenException(__('Access token is invalid!'));
        }
    }
}
