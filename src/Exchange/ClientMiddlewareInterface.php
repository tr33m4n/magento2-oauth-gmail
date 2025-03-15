<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange;

use Google\Client;

interface ClientMiddlewareInterface
{
    /**
     * Apply middleware to Google client
     */
    public function apply(Client $client): Client;
}
