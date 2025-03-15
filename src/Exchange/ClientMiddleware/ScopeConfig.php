<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange\ClientMiddleware;

use Google\Client;
use Google\Service\Gmail;
use tr33m4n\OauthGmail\Exchange\ClientMiddlewareInterface;

class ScopeConfig implements ClientMiddlewareInterface
{
    /**
     * Configure scope config
     */
    public function apply(Client $client): Client
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
