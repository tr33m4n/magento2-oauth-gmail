<?php

namespace tr33m4n\OauthGmail\Model;

use Google\Client;
use Google_Service_Gmail;

/**
 * Class GetGoogleClient
 *
 * @package tr33m4n\OauthGmail\Model
 */
class GetGoogleClient
{
    /**
     * @var \tr33m4n\OauthGmail\Model\GetAuthConfig
     */
    private $getAuthConfig;

    /**
     * @var \tr33m4n\OauthGmail\Model\ConfigureAccessToken
     */
    private $configureAccessToken;

    /**
     * @var \Google\Client|null
     */
    private $configuredClient;

    /**
     * GetGoogleClient constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\GetAuthConfig        $getAuthConfig
     * @param \tr33m4n\OauthGmail\Model\ConfigureAccessToken $configureAccessToken
     */
    public function __construct(
        GetAuthConfig $getAuthConfig,
        ConfigureAccessToken $configureAccessToken
    ) {
        $this->getAuthConfig = $getAuthConfig;
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
        if ($this->configuredClient) {
            return $this->configuredClient;
        }

        $client = new Client();
        $client->setAuthConfig($this->getAuthConfig->execute());
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->addScope(Google_Service_Gmail::GMAIL_COMPOSE);
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);
        $client->addScope(Google_Service_Gmail::GMAIL_READONLY);

        return $this->configuredClient = $this->configureAccessToken->execute($client);
    }
}
