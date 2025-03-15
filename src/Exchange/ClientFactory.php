<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange;

use Google\Client;
use Google\ClientFactory as GoogleClientFactory;

class ClientFactory
{
    /**
     * ClientFactory constructor.
     */
    public function __construct(
        private readonly ClientMiddlewareInterface $middleware,
        private readonly GoogleClientFactory $googleClientFactory
    ) {
    }

    /**
     * Create Google client
     */
    public function create(): Client
    {
        return $this->middleware->apply($this->googleClientFactory->create());
    }
}
