<?php

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use tr33m4n\OauthGmail\Exception\AccessTokenException;
use tr33m4n\OauthGmail\Model\GetAccessToken;
use tr33m4n\OauthGmail\Model\SaveAccessToken;

/**
 * Class ConfigureAccessToken
 *
 * @package tr33m4n\OauthGmail\Model\Client
 */
class ConfigureAccessToken
{
    /**
     * @var \tr33m4n\OauthGmail\Model\SaveAccessToken
     */
    private $saveAccessToken;

    /**
     * @var \tr33m4n\OauthGmail\Model\GetAccessToken
     */
    private $getAccessToken;

    /**
     * ConfigureAccessToken constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\SaveAccessToken $saveAccessToken
     * @param \tr33m4n\OauthGmail\Model\GetAccessToken  $getAccessToken
     */
    public function __construct(
        SaveAccessToken $saveAccessToken,
        GetAccessToken $getAccessToken
    ) {
        $this->saveAccessToken = $saveAccessToken;
        $this->getAccessToken = $getAccessToken;
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
        $accessToken = $this->getAccessToken->execute();
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
