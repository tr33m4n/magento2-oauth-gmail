<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Exception;
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
     * @var \tr33m4n\GoogleOauthMail\Model\GetLatestAccessToken
     */
    private $getLatestAccessToken;

    /**
     * @var \Google\Client|null
     */
    private $configuredClient;

    /**
     * GetGoogleClient constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param \tr33m4n\GoogleOauthMail\Model\GetAuthConfig        $getAuthConfig
     * @param \tr33m4n\GoogleOauthMail\Model\GetLatestAccessToken $getLatestAccessToken
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        GetAuthConfig $getAuthConfig,
        GetLatestAccessToken $getLatestAccessToken
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->getAuthConfig = $getAuthConfig;
        $this->getLatestAccessToken = $getLatestAccessToken;
    }

    /**
     * Get configured Google client
     *
     * @throws \Google\Exception
     * @throws \Exception
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
        $client->setApprovalPrompt('force');
        $client->addScope(Google_Service_Gmail::GMAIL_COMPOSE);

        /** @var \tr33m4n\GoogleOauthMail\Model\Token $accessToken */
        if ($accessToken = $this->getLatestAccessToken->execute()) {
            $client->setAccessToken($accessToken->getData());

            // TODO: Refactor refresh handling
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $credentials = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

                    if (array_key_exists('error', $credentials)) {
                        throw new Exception('TODO');
                    }
                } else {
                    throw new Exception('TODO');
                }
            }
        }

        return $this->configuredClient = $client;
    }
}
