<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Google\ClientFactory;

class GetClient
{
    private ?Client $configuredClient = null;

    /**
     * GetClient constructor.
     */
    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly ConfigureAuthConfig $configureAuthConfig,
        private readonly ConfigureScopeConfig $configureScopeConfig,
        private readonly ConfigureAccessToken $configureAccessToken
    ) {
    }

    /**
     * Get configured Google client
     *
     * @throws \Google\Exception
     * @throws \Exception
     */
    public function execute(): Client
    {
        if ($this->configuredClient instanceof Client) {
            return $this->configuredClient;
        }

        /** @var \Google\Client $client */
        $client = $this->clientFactory->create();
        $this->configureAuthConfig->execute($client);
        $this->configureScopeConfig->execute($client);
        $this->configureAccessToken->execute($client);

        return $this->configuredClient = $client;
    }
}
