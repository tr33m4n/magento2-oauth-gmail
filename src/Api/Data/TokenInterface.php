<?php

namespace tr33m4n\GoogleOauthMail\Api\Data;

/**
 * Interface TokenInterface
 *
 * @package tr33m4n\GoogleOauthMail\Api\Data
 */
interface TokenInterface
{
    const KEY_ACCESS_TOKEN = 'access_token';

    const KEY_EXPIRES_IN = 'expires_in';

    const KEY_SCOPE = 'scope';

    const KEY_CREATED_AT = 'created_at';

    /**
     * Get access token
     *
     * @return string|null
     */
    public function getAccessToken() : ?string;

    /**
     * Set access token
     *
     * @param string $accessToken
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setAccessToken(string $accessToken) : TokenInterface;

    /**
     * Get scope
     *
     * @return string|null
     */
    public function getScope() : ?string;

    /**
     * Set scope
     *
     * @param string $scope
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setScope(string $scope) : TokenInterface;

    /**
     * Get expires in
     *
     * @return string|null
     */
    public function getExpiresIn() : ?string;

    /**
     * Set expires in
     *
     * @param int $expiresIn
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setExpiresIn(int $expiresIn) : TokenInterface;

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt() : ?string;

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setCreatedAt(string $createdAt) : TokenInterface;
}
