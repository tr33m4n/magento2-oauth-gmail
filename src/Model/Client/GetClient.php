<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Google\ClientFactory;

class GetClient
{
    private ClientFactory $clientFactory;

    private ConfigureAuthConfig $configureAuthConfig;

    private ConfigureScopeConfig $configureScopeConfig;

    private ConfigureAccessToken $configureAccessToken;

    private ?Client $configuredClient = null;

    /**
     * GetClient constructor.
     *
     * @param \Google\ClientFactory                                 $clientFactory
     * @param \tr33m4n\OauthGmail\Model\Client\ConfigureAuthConfig  $configureAuthConfig
     * @param \tr33m4n\OauthGmail\Model\Client\ConfigureScopeConfig $configureScopeConfig
     * @param \tr33m4n\OauthGmail\Model\Client\ConfigureAccessToken $configureAccessToken
     */
    public function __construct(
        ClientFactory $clientFactory,
        ConfigureAuthConfig $configureAuthConfig,
        ConfigureScopeConfig $configureScopeConfig,
        ConfigureAccessToken $configureAccessToken
    ) {
        $this->clientFactory = $clientFactory;
        $this->configureAuthConfig = $configureAuthConfig;
        $this->configureScopeConfig = $configureScopeConfig;
        $this->configureAccessToken = $configureAccessToken;
    }

    /**
     * Get configured Google client
     *
     * @throws \Google\Exception
     * @throws \Exception
     * @return \Google\Client
     */
    public function execute() : Client
    {
        if ($this->configuredClient !== null) {
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
