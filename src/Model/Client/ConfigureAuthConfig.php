<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use tr33m4n\OauthGmail\Exception\AuthConfigException;
use tr33m4n\OauthGmail\Model\Config\Backend\AuthFile;
use tr33m4n\OauthGmail\Model\Config\Source\AuthType;

class ConfigureAuthConfig
{
    private const XML_CONFIG_AUTH_TYPE = 'system/oauth_gmail/auth_type';

    private const XML_CONFIG_AUTH_FILE = 'system/oauth_gmail/auth_file';

    private const XML_CONFIG_CLIENT_ID_PATH = 'system/oauth_gmail/client_id';

    private const XML_CONFIG_CLIENT_SECRET_PATH = 'system/oauth_gmail/client_secret';

    private ScopeConfigInterface $scopeConfig;

    private UrlInterface $url;

    private ReadInterface $varDirectory;

    /**
     * ConfigureAuthConfig constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Filesystem                      $filesystem
     * @param \Magento\Backend\Model\UrlInterface                $url
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        UrlInterface $url
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
        $this->varDirectory = $filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
    }

    /**
     * Configure auth config
     *
     * @throws \Google\Exception
     * @throws \tr33m4n\OauthGmail\Exception\AuthConfigException
     * @param \Google\Client $client
     * @return \Google\Client
     */
    public function execute(Client $client): Client
    {
        switch ($this->scopeConfig->getValue(self::XML_CONFIG_AUTH_TYPE)) {
            case AuthType::AUTH_TYPE_FILE:
                $authFile = $this->varDirectory->getAbsolutePath(
                    sprintf(
                        '%s%s%s',
                        AuthFile::ROOT_PATH,
                        DIRECTORY_SEPARATOR,
                        $this->scopeConfig->getValue(self::XML_CONFIG_AUTH_FILE)
                    )
                );

                if (!$this->varDirectory->isExist($authFile)) {
                    throw new AuthConfigException(__('Auth file does not exist'));
                }

                $client->setAuthConfig($authFile);
                break;
            case AuthType::AUTH_TYPE_CLIENT_ID_SECRET:
                $client->setAuthConfig([
                    'client_id' => $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_ID_PATH),
                    'client_secret' => $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_SECRET_PATH),
                    'redirect_uris' => [
                        $this->getRedirectUrl()
                    ]
                ]);
                break;
            default:
                throw new AuthConfigException(__('Invalid auth type'));
        }

        return $client;
    }

    /**
     * Get redirect URL
     *
     * @return string
     */
    private function getRedirectUrl(): string
    {
        $this->url->turnOffSecretKey();
        $callbackUrl = $this->url->getUrl('oauthgmail/callback/authenticate');
        $this->url->turnOnSecretKey();

        return $callbackUrl;
    }
}
