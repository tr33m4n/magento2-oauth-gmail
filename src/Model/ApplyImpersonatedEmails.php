<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Client;
use Laminas\Mail\AddressList;
use Laminas\Mail\Exception\InvalidArgumentException as LaminasInvalidArgumentException;
use Magento\Framework\Mail\AddressFactory;
use Magento\Framework\Mail\EmailMessage;
use Psr\Log\LoggerInterface;
use tr33m4n\OauthGmail\Model\Config\Provider;

class ApplyImpersonatedEmails
{
    private Provider $configProvider;

    private AddressFactory $addressFactory;

    private LoggerInterface $logger;

    /**
     * ApplyImpersonatedEmails constructor.
     */
    public function __construct(
        Provider $configProvider,
        AddressFactory $addressFactory,
        LoggerInterface $logger
    ) {
        $this->configProvider = $configProvider;
        $this->addressFactory = $addressFactory;
        $this->logger = $logger;
    }

    /**
     * Apply impersonated email config to message
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
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
