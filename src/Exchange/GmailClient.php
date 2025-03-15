<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange;

use Google\Service\Gmail;
use Google\Service\Gmail\Resource\UsersMessages;
use Google\Service\Gmail\Resource\UsersSettingsSendAs;
use tr33m4n\OauthGmail\Api\GmailClientInterface;
use tr33m4n\OauthGmail\Exception\ClientException;

class GmailClient implements GmailClientInterface
{
    private ?Gmail $gmailClient = null;

    /**
     * GmailClient constructor.
     */
    public function __construct(
        private readonly GmailClientFactory $gmailClientFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getUsersMessages(): UsersMessages
    {
        $usersMessages = $this->getGmailClient()->users_messages;
        if (!$usersMessages instanceof UsersMessages) {
            throw new ClientException(__('Unable to query users messages'));
        }

        return $usersMessages;
    }

    /**
     * @inheritDoc
     */
    public function getUsersSettingsSendAs(): UsersSettingsSendAs
    {
        $usersSettingsSendAs = $this->getGmailClient()->users_settings_sendAs;
        if (!$usersSettingsSendAs instanceof UsersSettingsSendAs) {
            throw new ClientException(__('Unable to query users settings send as'));
        }

        return $usersSettingsSendAs;
    }

    /**
     * @inheritDoc
     */
    public function getDelegatedAccount(): ?string
    {
        $delegatedAccount = $this->getGmailClient()->getClient()->getConfig('subject');
        if (!is_string($delegatedAccount)) {
            return null;
        }

        return $delegatedAccount;
    }

    /**
     * @inheritDoc
     */
    public function setDelegatedAccount(string $delegatedAccount): GmailClientInterface
    {
        $this->getGmailClient()->getClient()->setSubject($delegatedAccount);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAccessTokenWithAuthCode(string $code): array
    {
        $accessToken = $this->getGmailClient()->getClient()->fetchAccessTokenWithAuthCode($code);

        /** @var array<string, mixed> $accessToken */
        return $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function getAuthUrl(): string
    {
        return $this->getGmailClient()->getClient()->createAuthUrl();
    }

    /**
     * Get Gmail client
     */
    private function getGmailClient(): Gmail
    {
        return $this->gmailClient instanceof Gmail
            ? $this->gmailClient
            : $this->gmailClient = $this->gmailClientFactory->create();
    }
}
