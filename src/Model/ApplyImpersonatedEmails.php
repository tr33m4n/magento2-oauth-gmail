<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Client;
use Magento\Framework\Mail\EmailMessage;
use tr33m4n\OauthGmail\Model\Config\Provider;

class ApplyImpersonatedEmails
{
    /**
     * ApplyImpersonatedEmails constructor.
     */
    public function __construct(
        private readonly Provider $configProvider
    ) {
    }

    /**
     * Apply impersonated email config to message
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute(EmailMessage $emailMessage, Client $client): EmailMessage
    {
        if (!$this->configProvider->shouldUseImpersonated()) {
            return $emailMessage;
        }

        $impersonatedEmailConfig = $this->configProvider->getImpersonatedEmails();
        if ([] === $impersonatedEmailConfig) {
            return $emailMessage;
        }

        foreach ($emailMessage->getFrom() as $address) {
            $addressEmail = $address->getEmail();
            if (!array_key_exists($addressEmail, $impersonatedEmailConfig)) {
                continue;
            }

            $client->setSubject($impersonatedEmailConfig[$addressEmail]);
            break;
        }

        return $emailMessage;
    }
}
