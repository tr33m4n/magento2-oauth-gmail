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

    const KEY_REFRESH_TOKEN = 'refresh_token';

    const KEY_TOKEN_TYPE = 'token_type';

    const KEY_SCOPE = 'scope';

    const KEY_EXPIRES_IN = 'expires_in';

    const KEY_CREATED = 'created';

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
     * Get refresh token
     *
     * @return string|null
     */
    public function getRefreshToken() : ?string;

    /**
     * Set refresh token
     *
     * @param string $refreshToken
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setRefreshToken(string $refreshToken) : TokenInterface;

    /**
     * Get token type
     *
     * @return string|null
     */
    public function getTokenType() : ?string;

    /**
     * Set token type
     *
     * @param string $tokenType
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setTokenType(string $tokenType) : TokenInterface;

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
     * @return int|null
     */
    public function getExpiresIn() : ?int;

    /**
     * Set expires in
     *
     * @param int $expiresIn
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setExpiresIn(int $expiresIn) : TokenInterface;

    /**
     * Get created
     *
     * @return int|null
     */
    public function getCreated() : ?int;

    /**
     * Set created
     *
     * @param int $created
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface
     */
    public function setCreated(int $created) : TokenInterface;
}
