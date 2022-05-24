<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Client;

use Google\Client;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigureAuthConfig
{
    private const XML_CONFIG_CLIENT_ID_PATH = 'system/oauth_gmail/client_id';

    private const XML_CONFIG_CLIENT_SECRET_PATH = 'system/oauth_gmail/client_secret';

    private ScopeConfigInterface $scopeConfig;

    private UrlInterface $url;

    /**
     * ConfigureAuthConfig constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Backend\Model\UrlInterface                $url
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UrlInterface $url
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
    }

    /**
     * Configure auth config
     *
     * @throws \Google\Exception
     * @param \Google\Client $client
     * @return \Google\Client
     */
    public function execute(Client $client) : Client
    {
        $client->setAuthConfig([
            'client_id' => $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_ID_PATH),
            'client_secret' => $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_SECRET_PATH),
            'redirect_uris' => [
                $this->getRedirectUrl()
            ]
        ]);

        return $client;
    }

    /**
     * Get redirect URL
     *
     * @return string
     */
    private function getRedirectUrl() : string
    {
        $this->url->turnOffSecretKey();
        $callbackUrl = $this->url->getUrl('oauthgmail/callback/authenticate');
        $this->url->turnOnSecretKey();

        return $callbackUrl;
    }
}
