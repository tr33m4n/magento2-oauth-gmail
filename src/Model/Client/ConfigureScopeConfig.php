<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Google\Service\Gmail;

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
        $client->addScope(Gmail::GMAIL_COMPOSE);
        $client->addScope(Gmail::GMAIL_SEND);
        $client->addScope(Gmail::GMAIL_READONLY);

        return $client;
    }
}
