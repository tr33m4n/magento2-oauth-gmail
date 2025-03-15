<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Magento\Framework\Mail\EmailMessage;
use tr33m4n\OauthGmail\Api\GmailClientInterface;
use tr33m4n\OauthGmail\Model\Config\Provider;

class DelegateEmails
{
    /**
     * DelegateEmails constructor.
     */
    public function __construct(
        private readonly GmailClientInterface $gmailClient,
        private readonly Provider $configProvider
    ) {
    }

    /**
     * Apply delegated email config to message
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute(EmailMessage $emailMessage): EmailMessage
    {
        if (!$this->configProvider->shouldUseDelegated()) {
            return $emailMessage;
        }

        $delegatedEmailConfig = $this->configProvider->getDelegatedEmails();
        if ([] === $delegatedEmailConfig) {
            return $emailMessage;
        }

        foreach (($emailMessage->getFrom() ?? []) as $address) {
            $addressEmail = $address->getEmail() ?? '';
            if (!array_key_exists($addressEmail, $delegatedEmailConfig)) {
                continue;
            }

            $this->gmailClient->setDelegatedAccount($delegatedEmailConfig[$addressEmail]);
            break;
        }

        return $emailMessage;
    }
}
