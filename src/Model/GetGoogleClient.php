<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Google\Client;
use Google_Service_Gmail;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class GetGoogleClient
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class GetGoogleClient
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\GetAuthConfig
     */
    private $getAuthConfig;

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\GetAccessToken
     */
    private $getAccessToken;

    /**
     * @var \Google\Client|null
     */
    private $configuredClient;

    /**
     * GetGoogleClient constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \tr33m4n\GoogleOauthMail\Model\GetAuthConfig       $getAuthConfig
     * @param \tr33m4n\GoogleOauthMail\Model\GetAccessToken      $getAccessToken
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        GetAuthConfig $getAuthConfig,
        GetAccessToken $getAccessToken
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->getAuthConfig = $getAuthConfig;
        $this->getAccessToken = $getAccessToken;
    }

    /**
     * Get configured Google client
     *
     * @throws \Google\Exception
     * @return \Google\Client
     */
    public function execute() : Client
    {
        if ($this->configuredClient) {
            return $this->configuredClient;
        }

        $client = new Client();
        $client->setAuthConfig($this->getAuthConfig->execute());
        $client->setAccessType('offline');
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);

        if ($accessToken = $this->getAccessToken->execute()) {
            $client->setAccessToken($accessToken);
        }

        return $this->configuredClient = $client;
    }
}
