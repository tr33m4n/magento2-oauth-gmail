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
     * @var \Google\Client|null
     */
    private $configuredClient;

    /**
     * GetGoogleClient constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \tr33m4n\GoogleOauthMail\Model\GetAuthConfig       $getAuthConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        GetAuthConfig $getAuthConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->getAuthConfig = $getAuthConfig;
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
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);

        return $this->configuredClient = $client;
    }
}
