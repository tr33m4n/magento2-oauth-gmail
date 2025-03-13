<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Google\Service\Gmail;

class ConfigureScopeConfig
{
    /**
     * Configure scope config
     */
    public function execute(Client $client) : Client
    {
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setPrompt('select_account');
        $client->addScope(Gmail::GMAIL_COMPOSE);
        $client->addScope(Gmail::GMAIL_SEND);
        $client->addScope(Gmail::GMAIL_READONLY);

        return $client;
    }
}
