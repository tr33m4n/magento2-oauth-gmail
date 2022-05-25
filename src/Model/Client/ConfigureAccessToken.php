<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Psr\Log\LoggerInterface;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Exception\AccessTokenException;
use tr33m4n\OauthGmail\Model\GetAccessToken;
use tr33m4n\OauthGmail\Model\SaveAccessToken;
use tr33m4n\OauthGmail\Model\ValidateAccessToken;

class ConfigureAccessToken
{
    private SaveAccessToken $saveAccessToken;

    private GetAccessToken $getAccessToken;

    private ValidateAccessToken $validateAccessToken;

    private LoggerInterface $logger;

    /**
     * ConfigureAccessToken constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\SaveAccessToken     $saveAccessToken
     * @param \tr33m4n\OauthGmail\Model\GetAccessToken      $getAccessToken
     * @param \tr33m4n\OauthGmail\Model\ValidateAccessToken $validateAccessToken
     * @param \Psr\Log\LoggerInterface                      $logger
     */
    public function __construct(
        SaveAccessToken $saveAccessToken,
        GetAccessToken $getAccessToken,
        ValidateAccessToken $validateAccessToken,
        LoggerInterface $logger
    ) {
        $this->saveAccessToken = $saveAccessToken;
        $this->getAccessToken = $getAccessToken;
        $this->validateAccessToken = $validateAccessToken;
        $this->logger = $logger;
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
        /** @var \tr33m4n\OauthGmail\Model\Token|null $accessToken */
        $accessToken = $this->getAccessToken->execute();
        if (!$accessToken instanceof TokenInterface) {
            return $client;
        }

        $client->setAccessToken($accessToken->toArray());
        if (!$client->isAccessTokenExpired()) {
            return $client;
        }

        if (!$client->getRefreshToken()) {
            throw new AccessTokenException(
                __('Access token and refresh token are not available to configure client!')
            );
        }

        $accessTokenCredentials = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

        try {
            $this->validateAccessToken->execute($accessTokenCredentials);
        } catch (AccessTokenException $accessTokenException) {
            $this->logger->error($accessTokenException);

            return $client;
        }

        $this->saveAccessToken->execute($accessTokenCredentials);

        return $client;
    }
}
