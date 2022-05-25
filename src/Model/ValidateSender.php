<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Exception;
use Magento\Framework\Mail\EmailMessage;
use tr33m4n\OauthGmail\Exception\SenderVerificationException;

class ValidateSender
{
    const UNSPECIFIED_STATUS = 'VERIFICATION_STATUS_UNSPECIFIED';

    const PENDING_STATUS = 'PENDING';

    private GetGmailService $getGmailService;

    /**
     * ValidateSender constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\GetGmailService $getGmailService
     */
    public function __construct(
        GetGmailService $getGmailService
    ) {
        $this->getGmailService = $getGmailService;
    }

    /**
     * Validate sender credentials with Google
     *
     * @throws \Google\Exception
     * @throws \tr33m4n\OauthGmail\Exception\SenderVerificationException
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
                throw new SenderVerificationException(
                    __(
                        'The email address %1 has not been configured: %2',
                        $address->getEmail(),
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
