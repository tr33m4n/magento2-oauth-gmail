<?php

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Google_Service_Gmail;

/**
 * Class ConfigureScopeConfig
 *
 * @package tr33m4n\OauthGmail\Model\Client
 */
class ConfigureScopeConfig
{
    /**
     * Configure scope config
     *
     * @param \Google\Client $client
     * @return \Google\Client
     */
    public function execute(Client $client) : Client
    {
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->addScope(Google_Service_Gmail::GMAIL_COMPOSE);
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);
        $client->addScope(Google_Service_Gmail::GMAIL_READONLY);

        return $client;
    }
}
