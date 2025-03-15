<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange;

use Google\Client;
use tr33m4n\OauthGmail\Exception\ClientMiddlewareException;

class ClientMiddlewareComposite implements ClientMiddlewareInterface
{
    /**
     * ClientMiddlewareComposite constructor.
     *
     * @param \tr33m4n\OauthGmail\Exchange\ClientMiddlewareInterface[]|mixed[] $middlewares
     */
    public function __construct(
        private readonly array $middlewares
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws \tr33m4n\OauthGmail\Exception\ClientMiddlewareException
     */
    public function apply(Client $client): Client
    {
        foreach ($this->middlewares as $middleware) {
            if (!$middleware instanceof ClientMiddlewareInterface) {
                throw new ClientMiddlewareException(
                    __('Client middleware is not an instance of %1', ClientMiddlewareInterface::class)
                );
            }

            $middleware->apply($client);
        }

        return $client;
    }
}
