<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Service\Gmail;
use Google\Service\GmailFactory;
use tr33m4n\OauthGmail\Model\Client\GetClient;

class GetGmailService
{
    private GetClient $getClient;

    private GmailFactory $gmailFactory;

    private ?Gmail $gmailService = null;

    /**
     * GetGmailService constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\Client\GetClient $getClient
     * @param \Google\Service\GmailFactory               $gmailFactory
     */
    public function __construct(
        GetClient $getClient,
        GmailFactory $gmailFactory
    ) {
        $this->getClient = $getClient;
        $this->gmailFactory = $gmailFactory;
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

        return $this->gmailService = $this->gmailFactory->create([
            'clientOrConfig' => $this->getClient->execute()
        ]);
    }
}
