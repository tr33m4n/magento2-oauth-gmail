<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Api;

use tr33m4n\OauthGmail\Api\Data\TokenInterface;

interface TokenRepositoryInterface
{
    /**
     * Get latest access token
     */
    public function getLatest(): ?TokenInterface;

    /**
     * Save access token
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(TokenInterface $token): TokenInterface;
}
