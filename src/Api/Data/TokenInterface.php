<?php

namespace tr33m4n\GoogleOauthMail\Api\Data;

/**
 * Interface TokenInterface
 *
 * @package tr33m4n\GoogleOauthMail\Api\Data
 */
interface TokenInterface
{
    const KEY_TOKEN = 'token';

    /**
     * Get token
     *
     * @return string
     */
    public function getToken() : string;

    /**
     * Set token
     *
     * @param string $token
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setToken(string $token) : TokenInterface;
}
