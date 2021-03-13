<?php

namespace tr33m4n\GoogleOauthMail\Model;

use tr33m4n\GoogleOauthMail\Exception\AccessTokenException;

/**
 * Class ValidateAccessToken
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class ValidateAccessToken
{
    /**
     * Validate access token
     *
     * @throws \tr33m4n\GoogleOauthMail\Exception\AccessTokenException
     * @param array $accessToken
     */
    public function execute(array $accessToken) : void
    {
        foreach (['access_token', 'expires_in', 'scope'] as $key) {
            if (!array_key_exists($key, $accessToken)) {
                throw new AccessTokenException(__('Access token is invalid!'));
            }
        }
    }
}
