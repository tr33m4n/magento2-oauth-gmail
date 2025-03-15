<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Api;

use Google\Service\Gmail\Resource\UsersMessages;
use Google\Service\Gmail\Resource\UsersSettingsSendAs;

interface GmailClientInterface
{
    /**
     * Get users messages
     *
     * @return \Google\Service\Gmail\Resource\UsersMessages
     * @throws \tr33m4n\OauthGmail\Exception\ClientException
     */
    public function getUsersMessages(): UsersMessages;

    /**
     * Get users settings send as
     *
     * @return \Google\Service\Gmail\Resource\UsersSettingsSendAs
     * @throws \tr33m4n\OauthGmail\Exception\ClientException
     */
    public function getUsersSettingsSendAs(): UsersSettingsSendAs;

    /**
     * Get delegated account
     *
     * @return string|null
     */
    public function getDelegatedAccount(): ?string;

    /**
     * Set delegated account
     *
     * @param string $delegatedAccount
     * @return \tr33m4n\OauthGmail\Api\GmailClientInterface
     */
    public function setDelegatedAccount(string $delegatedAccount): GmailClientInterface;

    /**
     * Get access token with auth code
     *
     * @param string $code
     * @return array<string, mixed>
     */
    public function getAccessTokenWithAuthCode(string $code): array;

    /**
     * Get authentication URL
     *
     * @return string
     */
    public function getAuthUrl(): string;
}
