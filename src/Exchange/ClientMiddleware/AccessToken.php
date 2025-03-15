<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange\ClientMiddleware;

use Google\Client;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Api\TokenRepositoryInterface;
use tr33m4n\OauthGmail\Exception\AccessTokenException;
use tr33m4n\OauthGmail\Exchange\ClientMiddlewareInterface;
use tr33m4n\OauthGmail\Model\TokenFactory;

class AccessToken implements ClientMiddlewareInterface
{
    /**
     * AccessToken constructor.
     */
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly TokenFactory $tokenFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * Configure access token
     *
     * @throws \tr33m4n\OauthGmail\Exception\AccessTokenException
     */
    public function apply(Client $client): Client
    {
        $accessToken = $this->tokenRepository->getLatest();
        if (!$accessToken instanceof TokenInterface) {
            return $client;
        }

        /** @var \tr33m4n\OauthGmail\Model\Token $accessToken */
        $client->setAccessToken($accessToken->toArray());
        if (!$client->isAccessTokenExpired()) {
            return $client;
        }

        if (!$client->getRefreshToken()) {
            throw new AccessTokenException(
                __('Access token and refresh token are not available to configure client!')
            );
        }

        /** @var array<string, mixed> $accessTokenCredentials */
        $accessTokenCredentials = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

        try {
            $this->tokenRepository->save($this->tokenFactory->create($accessTokenCredentials));
        } catch (LocalizedException $localizedException) {
            $this->logger->error($localizedException);
        }

        return $client;
    }
}
