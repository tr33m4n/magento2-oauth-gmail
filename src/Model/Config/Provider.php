<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use tr33m4n\OauthGmail\Exception\ConfigException;

class Provider
{
    private const XML_CONFIG_AUTH_TYPE = 'system/oauth_gmail/auth_type';

    private const XML_CONFIG_AUTH_FILE = 'system/oauth_gmail/auth_file';

    private const XML_CONFIG_USE_IMPERSONATED = 'system/oauth_gmail/use_impersonated';

    private const XML_CONFIG_IMPERSONATED_EMAILS = 'system/oauth_gmail/impersonated_emails';

    private const XML_CONFIG_CLIENT_ID_PATH = 'system/oauth_gmail/client_id';

    private const XML_CONFIG_CLIENT_SECRET_PATH = 'system/oauth_gmail/client_secret';

    private const AUTH_PATH_TEMPLATE = 'oauth_gmail' . DIRECTORY_SEPARATOR . '%s';

    private const SERVICE_ACCOUNT_VALUE = 'service_account';

    public const EMAIL_KEY = 'email';

    public const SCOPES_KEY = 'scopes';

    private ReadInterface $varDirectory;

    /**
     * @var array<string, string>
     */
    private ?array $impersonatedEmails = null;

    private ?bool $isServiceAccount = null;

    /**
     * Provider constructor.
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SenderResolverInterface $senderResolver,
        Filesystem $filesystem
    ) {
        $this->varDirectory = $filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
    }

    /**
     * Get auth type
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function getAuthType(): string
    {
        $authType = $this->scopeConfig->getValue(self::XML_CONFIG_AUTH_TYPE);
        if (!is_string($authType)) {
            throw new ConfigException(__('Invalid auth type'));
        }

        return $authType;
    }

    /**
     * Get auth file
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function getAuthFile(): string
    {
        $authFilePath = $this->scopeConfig->getValue(self::XML_CONFIG_AUTH_FILE);
        if (!is_string($authFilePath)) {
            throw new ConfigException(__('Invalid auth file'));
        }

        return $authFilePath;
    }

    /**
     * Get auth file path
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function getAuthFilePath(): string
    {
        $authFilePath = $this->varDirectory->getAbsolutePath(sprintf(self::AUTH_PATH_TEMPLATE, $this->getAuthFile()));
        if (!$this->varDirectory->isExist($authFilePath)) {
            throw new ConfigException(__('Auth file "%1" does not exist', $authFilePath));
        }

        return $authFilePath;
    }

    /**
     * Check if service account
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function isServiceAccount(): bool
    {
        if (null !== $this->isServiceAccount) {
            return $this->isServiceAccount;
        }

        try {
            $authFileData = $this->serializer->unserialize($this->varDirectory->readFile($this->getAuthFilePath()));
        } catch (InvalidArgumentException | ConfigException $exception) {
            return false;
        }

        return $this->isServiceAccount = ($authFileData['type'] ?? null) === self::SERVICE_ACCOUNT_VALUE;
    }

    /**
     * Get client ID
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function getClientId(): string
    {
        $clientId = $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_ID_PATH);
        if (!is_string($clientId)) {
            throw new ConfigException(__('Client ID not set'));
        }

        return $clientId;
    }

    /**
     * Get client secret
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function getClientSecret(): string
    {
        $clientSecret = $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_SECRET_PATH);
        if (!is_string($clientSecret)) {
            throw new ConfigException(__('Client secret not set'));
        }

        return $clientSecret;
    }

    /**
     * Get test scope
     */
    public function getTestScope(): ?string
    {
        $testScope = $this->scopeConfig->getValue('system/oauth_gmail/test_scope');
        if (!is_string($testScope)) {
            return null;
        }

        return $testScope;
    }

    /**
     * Whether to use an impersonated account
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function shouldUseImpersonated(): bool
    {
        return $this->isServiceAccount() && $this->scopeConfig->isSetFlag(self::XML_CONFIG_USE_IMPERSONATED);
    }

    /**
     * Get impersonated emails
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @throws \Magento\Framework\Exception\MailException
     * @return array<string, string>
     */
    public function getImpersonatedEmails(): array
    {
        if (null !== $this->impersonatedEmails) {
            return $this->impersonatedEmails;
        }

        $impersonatedEmails = $this->scopeConfig->getValue(self::XML_CONFIG_IMPERSONATED_EMAILS);
        if (is_string($impersonatedEmails)) {
            $impersonatedEmails = $this->serializer->unserialize($impersonatedEmails);
        }

        if (empty($impersonatedEmails)) {
            throw new ConfigException(__('No impersonated emails have been set'));
        }

        $indexedEmails = [];
        foreach ($impersonatedEmails as $impersonatedEmail) {
            $scopes = $impersonatedEmail[self::SCOPES_KEY] ?? [];

            foreach ($scopes as $scope) {
                $resolvedEmail = $this->senderResolver->resolve($scope)[self::EMAIL_KEY] ?? null;
                if (!$resolvedEmail) {
                    continue;
                }

                $indexedEmails[$resolvedEmail] = $impersonatedEmail[self::EMAIL_KEY];
            }
        }

        return $this->impersonatedEmails = $indexedEmails;
    }

    /**
     * Check whether auth credentials have been set
     */
    public function hasAuthCredentials(): bool
    {
        try {
            return (bool) $this->getAuthFilePath();
        } catch (ConfigException) {
            // Do nothing
        }

        try {
            return $this->getClientId() && $this->getClientSecret();
        } catch (ConfigException) {
            // Do nothing
        }

        return false;
    }
}
