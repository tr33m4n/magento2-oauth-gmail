<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Exception;
use Google\Service\Gmail\SendAs;
use Magento\Framework\Mail\EmailMessage;
use tr33m4n\OauthGmail\Exception\SenderVerificationException;
use tr33m4n\OauthGmail\Model\Config\Provider;

class ValidateSender
{
    const UNSPECIFIED_STATUS = 'VERIFICATION_STATUS_UNSPECIFIED';

    const PENDING_STATUS = 'PENDING';

    private GetGmailService $getGmailService;

    private Provider $configProvider;

    /**
     * ValidateSender constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\GetGmailService $getGmailService
     * @param \tr33m4n\OauthGmail\Model\Config\Provider $configProvider
     */
    public function __construct(
        GetGmailService $getGmailService,
        Provider $configProvider
    ) {
        $this->getGmailService = $getGmailService;
        $this->configProvider = $configProvider;
    }

    /**
     * Validate sender credentials with Google
     *
     * @throws \Google\Exception
     * @throws \tr33m4n\OauthGmail\Exception\SenderVerificationException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @param \Magento\Framework\Mail\EmailMessage $emailMessage
     */
    public function execute(EmailMessage $emailMessage) : void
    {
        foreach ((array) $emailMessage->getFrom() as $address) {
            try {
                $result = $this->getGmailService->execute()
                    ->users_settings_sendAs
                    ->get('me', $address->getEmail());
            } catch (Exception $exception) {
                $sendAsMessage = null;

                if ($this->configProvider->isServiceAccount() && $this->configProvider->shouldUseImpersonated()) {
                    $sendAsList = $this->getGmailService->execute()
                        ->users_settings_sendAs
                        ->listUsersSettingsSendAs('me')
                        ->getSendAs();

                    $subject = $this->getGmailService->execute()
                        ->getClient()
                        ->getConfig('subject');

                    $sendAsMessage = __(
                        '. Account %1 can only send as %2',
                        $subject,
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
