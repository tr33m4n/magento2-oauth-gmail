<?php

namespace tr33m4n\OauthGmail\Model;

use Google_Service_Gmail;

/**
 * Class GetGmailService
 *
 * @package tr33m4n\OauthGmail\Model
 */
class GetGmailService
{
    /**
     * @var \tr33m4n\OauthGmail\Model\GetGoogleClient
     */
    private $getGoogleClient;

    /**
     * GetGmailService constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\GetGoogleClient $getGoogleClient
     */
    public function __construct(
        GetGoogleClient $getGoogleClient
    ) {
        $this->getGoogleClient = $getGoogleClient;
    }

    /**
     * Get Gmail service
     *
     * @throws \Google\Exception
     * @return \Google_Service_Gmail
     */
    public function execute() : Google_Service_Gmail
    {
        return new Google_Service_Gmail($this->getGoogleClient->execute());
    }
}
