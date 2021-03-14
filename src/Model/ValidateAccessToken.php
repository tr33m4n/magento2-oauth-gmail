<?php

namespace tr33m4n\GoogleOauthMail\Model;

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

    /**
     * Validate access token
     *
     * @throws \tr33m4n\GoogleOauthMail\Exception\AccessTokenException
     * @param array $accessToken
     */
    public function execute(array $accessToken) : void
    {
        $hasKeyCount = count($accessToken) === count(self::REQUIRED_FIELDS);
        $hasCorrectKeys = empty(array_diff_key(self::REQUIRED_FIELDS, $accessToken));

        if (!$hasKeyCount || !$hasCorrectKeys) {
            throw new AccessTokenException(__('Access token is invalid!'));
        }
    }
}
