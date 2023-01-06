<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use tr33m4n\OauthGmail\Exception\ConfigException;

class Provider
{
    private const XML_CONFIG_AUTH_TYPE = 'system/oauth_gmail/auth_type';

    private const XML_CONFIG_AUTH_FILE = 'system/oauth_gmail/auth_file';

    private const XML_CONFIG_CLIENT_ID_PATH = 'system/oauth_gmail/client_id';

    private const XML_CONFIG_CLIENT_SECRET_PATH = 'system/oauth_gmail/client_secret';

    private const AUTH_PATH_TEMPLATE = 'oauth_gmail' . DIRECTORY_SEPARATOR . '%s';

    private ScopeConfigInterface $scopeConfig;

    private ReadInterface $varDirectory;

    /**
     * Provider constructor.
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem
    ) {
        $this->scopeConfig = $scopeConfig;
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
