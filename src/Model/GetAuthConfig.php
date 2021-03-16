<?php

namespace tr33m4n\OauthGoogleMail\Model;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class GetAuthConfig
 *
 * @package tr33m4n\OauthGoogleMail\Model
 */
class GetAuthConfig
{
    private const XML_CONFIG_CLIENT_ID_PATH = 'system/google_oauth_mail/client_id';

    private const XML_CONFIG_CLIENT_SECRET_PATH = 'system/google_oauth_mail/client_secret';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $url;

    /**
     * GetAuthConfig constructor.
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
     * Get auth config
     *
     * @return array
     */
    public function execute() : array
    {
        return [
            'client_id' => $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_ID_PATH),
            'client_secret' => $this->scopeConfig->getValue(self::XML_CONFIG_CLIENT_SECRET_PATH),
            'redirect_uris' => [
                $this->getRedirectUrl()
            ]
        ];
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
