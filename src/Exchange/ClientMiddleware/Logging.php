<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange\ClientMiddleware;

use Google\Client;
use Psr\Log\LoggerInterface;
use tr33m4n\OauthGmail\Exchange\ClientMiddlewareInterface;

class Logging implements ClientMiddlewareInterface
{
    /**
     * Logging constructor.
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Configure logger
     */
    public function apply(Client $client): Client
    {
        $client->setLogger($this->logger);

        return $client;
    }
}
