<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Exception;
use Google\Service\Gmail\SendAs;
use Magento\Framework\Mail\EmailMessage;
use tr33m4n\OauthGmail\Exception\SenderVerificationException;
use tr33m4n\OauthGmail\Model\Config\Provider;
use tr33m4n\OauthGmail\Api\GmailClientInterface;

class ValidateSender
{
    private const UNSPECIFIED_STATUS = 'VERIFICATION_STATUS_UNSPECIFIED';

    private const PENDING_STATUS = 'PENDING';

    /**
     * ValidateSender constructor.
     */
    public function __construct(
        private readonly GmailClientInterface $gmailClient,
        private readonly Provider $configProvider
    ) {
    }

    /**
     * Validate sender credentials with Google
     *
     * @throws \Google\Exception
     * @throws \tr33m4n\OauthGmail\Exception\SenderVerificationException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \tr33m4n\OauthGmail\Exception\ClientException
     */
    public function execute(EmailMessage $emailMessage): void
    {
        /** @var \Laminas\Mail\Address\AddressInterface $address */
        foreach ((array) $emailMessage->getFrom() as $address) {
            try {
                $result = $this->gmailClient->getUsersSettingsSendAs()
                    ->get('me', $address->getEmail());
            } catch (Exception $exception) {
                $sendAsMessage = null;

                if ($this->configProvider->isServiceAccount() && $this->configProvider->shouldUseDelegated()) {
                    $sendAsList = $this->gmailClient->getUsersSettingsSendAs()
                        ->listUsersSettingsSendAs('me')
                        ->getSendAs();

                    $sendAsMessage = __(
                        '. Account %1 can only send as %2',
                        $this->gmailClient->getDelegatedAccount(),
                        implode(
                            ', ',
                            array_map(
                                static fn (SendAs $sendAs) => $sendAs->getSendAsEmail(),
                                $sendAsList
                            )
                        )
                    );
                }

                throw new SenderVerificationException(
                    __(
                        'The email address %1 has not been configured%2: %3',
                        $address->getEmail(),
                        $sendAsMessage,
                        $exception->getMessage()
                    ),
                    $exception
                );
            }

            if (in_array($result->getVerificationStatus(), [self::UNSPECIFIED_STATUS, self::PENDING_STATUS])) {
                throw new SenderVerificationException(
                    __('The email address %1 is pending verification!', $address->getEmail())
                );
            }
        }
    }
}
