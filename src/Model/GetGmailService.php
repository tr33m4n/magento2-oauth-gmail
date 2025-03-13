<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Service\Gmail;
use Google\Service\GmailFactory;
use tr33m4n\OauthGmail\Model\Client\GetClient;

class GetGmailService
{
    private ?Gmail $gmailService = null;

    /**
     * GetGmailService constructor.
     */
    public function __construct(
        private readonly GetClient $getClient,
        private readonly GmailFactory $gmailFactory
    ) {
    }

    /**
     * Get Gmail service
     *
     * @throws \Google\Exception
     */
    public function execute(): Gmail
    {
        if ($this->gmailService !== null) {
            return $this->gmailService;
        }

        return $this->gmailService = $this->gmailFactory->create([
            'clientOrConfig' => $this->getClient->execute()
        ]);
    }
}
