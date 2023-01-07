<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Magento\Backend\Model\UrlInterface;
use tr33m4n\OauthGmail\Exception\AuthConfigException;
use tr33m4n\OauthGmail\Model\Config\Provider;
use tr33m4n\OauthGmail\Model\Config\Source\AuthType;

class ConfigureAuthConfig
{
    private Provider $configProvider;

    private UrlInterface $url;

    /**
     * ConfigureAuthConfig constructor.
     */
    public function __construct(
        Provider $configProvider,
        UrlInterface $url
    ) {
        $this->configProvider = $configProvider;
        $this->url = $url;
    }

    /**
     * Configure auth config
     *
     * @throws \Google\Exception
     * @throws \tr33m4n\OauthGmail\Exception\AuthConfigException
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @param \Google\Client $client
     * @return \Google\Client
     */
    public function execute(Client $client): Client
    {
        switch ($this->configProvider->getAuthType()) {
            case AuthType::AUTH_TYPE_FILE:
                $client->setAuthConfig($this->configProvider->getAuthFilePath());
                break;
            case AuthType::AUTH_TYPE_CLIENT_ID_SECRET:
                $client->setAuthConfig([
                    'client_id' => $this->configProvider->getClientId(),
                    'client_secret' => $this->configProvider->getClientSecret(),
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
