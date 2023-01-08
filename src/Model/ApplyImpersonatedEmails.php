<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Client;
use Magento\Framework\Mail\EmailMessage;
use tr33m4n\OauthGmail\Model\Config\Provider;

class ApplyImpersonatedEmails
{
    private Provider $configProvider;

    /**
     * ApplyImpersonatedEmails constructor.
     */
    public function __construct(
        Provider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * Apply impersonated email config to message
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function execute(EmailMessage $emailMessage, Client $client): EmailMessage
    {
        if (!$this->configProvider->shouldUseImpersonated()) {
            return $emailMessage;
        }

        $impersonatedEmailConfig = $this->configProvider->getImpersonatedEmails();
        if (empty($impersonatedEmailConfig)) {
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
