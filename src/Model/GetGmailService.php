<?php

namespace tr33m4n\OauthGmail\Model;

use Google_Service_Gmail;
use tr33m4n\OauthGmail\Model\Client\GetClient;

/**
 * Class GetGmailService
 *
 * @package tr33m4n\OauthGmail\Model
 */
class GetGmailService
{
    /**
     * @var \tr33m4n\OauthGmail\Model\Client\GetClient
     */
    private $getClient;

    /**
     * @var Google_Service_Gmail|null
     */
    private $gmailService;

    /**
     * GetGmailService constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\Client\GetClient $getClient
     */
    public function __construct(
        GetClient $getClient
    ) {
        $this->getClient = $getClient;
    }

    /**
     * Get Gmail service
     *
     * @throws \Google\Exception
     * @return \Google_Service_Gmail
     */
    public function execute() : Google_Service_Gmail
    {
        if ($this->gmailService !== null) {
            return $this->gmailService;
        }

        return $this->gmailService = new Google_Service_Gmail($this->getClient->execute());
    }
}
