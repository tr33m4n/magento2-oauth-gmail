<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;

/**
 * Class GetAuthConfig
 *
 * @package tr33m4n\GoogleOauthMail\Model
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
     * @var \Magento\Framework\UrlInterface
     */
    private $url;

    /**
     * GetAuthConfig constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\UrlInterface                    $url
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
                $this->url->getUrl('google-oauth-mail/callback/authenticate')
            ]
        ];
    }
}
