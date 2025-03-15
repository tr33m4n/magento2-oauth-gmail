<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Exchange;

use Google\Service\Gmail;
use Google\Service\GmailFactory;

class GmailClientFactory
{
    /**
     * GmailClientFactory constructor.
     */
    public function __construct(
        private readonly ClientFactory $googleClientFactory,
        private readonly GmailFactory $gmailFactory
    ) {
    }

    /**
     * Create Gmail client
     */
    public function create(): Gmail
    {
        /** @var \Google\Service\Gmail $gmailClient */
        $gmailClient = $this->gmailFactory->create([
            'clientOrConfig' => $this->googleClientFactory->create()
        ]);

        return $gmailClient;
    }
}
