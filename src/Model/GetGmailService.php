<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Service\Gmail;
use tr33m4n\OauthGmail\Model\Client\GetClient;

class GetGmailService
{
    private GetClient $getClient;

    private ?Gmail $gmailService = null;

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
     * @return \Google\Service\Gmail
     */
    public function execute() : Gmail
    {
        if ($this->gmailService !== null) {
            return $this->gmailService;
        }

        return $this->gmailService = new Gmail($this->getClient->execute());
    }
}
