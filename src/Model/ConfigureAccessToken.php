<?php

namespace tr33m4n\OauthGmail\Model;

use Google\Client;
use tr33m4n\OauthGmail\Exception\AccessTokenException;

/**
 * Class ConfigureAccessToken
 *
 * @package tr33m4n\OauthGmail\Model
 */
class ConfigureAccessToken
{
    /**
     * @var \tr33m4n\OauthGmail\Model\SaveAccessToken
     */
    private $saveAccessToken;

    /**
     * @var \tr33m4n\OauthGmail\Model\GetLatestAccessToken
     */
    private $getLatestAccessToken;

    /**
     * ConfigureAccessToken constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\SaveAccessToken      $saveAccessToken
     * @param \tr33m4n\OauthGmail\Model\GetLatestAccessToken $getLatestAccessToken
     */
    public function __construct(
        SaveAccessToken $saveAccessToken,
        GetLatestAccessToken $getLatestAccessToken
    ) {
        $this->saveAccessToken = $saveAccessToken;
        $this->getLatestAccessToken = $getLatestAccessToken;
    }

    /**
     * Configure access token
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \tr33m4n\OauthGmail\Exception\AccessTokenException
     * @param \Google\Client $client
     * @return \Google\Client
     */
    public function execute(Client $client) : Client
    {
        /** @var \tr33m4n\OauthGmail\Model\Token $accessToken */
        $accessToken = $this->getLatestAccessToken->execute();
        if (!$accessToken) {
            return $client;
        }

        $client->setAccessToken($accessToken->getData());
        if (!$client->isAccessTokenExpired()) {
            return $client;
        }

        if (!$client->getRefreshToken()) {
            throw new AccessTokenException(
                __('Access token and refresh token are not available to configure client!')
            );
        }

        $this->saveAccessToken->execute(
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken())
        );

        return $client;
    }
}
