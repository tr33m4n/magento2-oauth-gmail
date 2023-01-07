<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config;

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

    public const EMAIL_KEY = 'email';

    public const SCOPES_KEY = 'scopes';

    private SerializerInterface $serializer;

    private ScopeConfigInterface $scopeConfig;

    private ReadInterface $varDirectory;

    private SenderResolverInterface $senderResolver;

    /**
     * @var array<string, string>
     */
    private ?array $impersonatedEmails = null;

    /**
     * Provider constructor.
     */
    public function __construct(
        SerializerInterface $serializer,
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        SenderResolverInterface $senderResolver
    ) {
        $this->serializer = $serializer;
        $this->scopeConfig = $scopeConfig;
        $this->varDirectory = $filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $this->senderResolver = $senderResolver;
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
     * Get auth file path
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function getAuthFilePath(): string
    {
        $authFilePath = $this->scopeConfig->getValue(self::XML_CONFIG_AUTH_FILE);
        if (!is_string($authFilePath)) {
            throw new ConfigException(__('Invalid auth file'));
        }

        $authFilePath = $this->varDirectory->getAbsolutePath(sprintf(self::AUTH_PATH_TEMPLATE, $authFilePath));
        if (!$this->varDirectory->isExist($authFilePath)) {
            throw new ConfigException(__('Auth file "%1" does not exist', $authFilePath));
        }

        return $authFilePath;
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
     * Whether to use an impersonated account
     */
    public function shouldUseImpersonated(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_USE_IMPERSONATED);
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
            if (empty($scopes)) {
                continue;
            }

            foreach ($scopes as $scope) {
                $resolvedEmail = $this->senderResolver->resolve($scope)[self::EMAIL_KEY] ?? null;
                if (!$resolvedEmail) {
                    continue;
                }

                $indexedEmails[$resolvedEmail] = $impersonatedEmails[self::EMAIL_KEY];
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
        } catch (ConfigException $configException) {
            // Do nothing
        }

        try {
            return $this->getClientId() && $this->getClientSecret();
        } catch (ConfigException $configException) {
            // Do nothing
        }

        return false;
    }
}
