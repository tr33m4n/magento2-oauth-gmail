<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Google\Exception;
use Magento\Framework\Mail\EmailMessage;
use tr33m4n\GoogleOauthMail\Exception\SenderVerificationException;

/**
 * Class ValidateSender
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class ValidateSender
{
    const UNSPECIFIED_STATUS = 'VERIFICATION_STATUS_UNSPECIFIED';

    const PENDING_STATUS = 'PENDING';

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\GetGmailService
     */
    private $getGmailService;

    /**
     * ValidateSender constructor.
     *
     * @param \tr33m4n\GoogleOauthMail\Model\GetGmailService $getGmailService
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
     * @throws \tr33m4n\GoogleOauthMail\Exception\SenderVerificationException
     * @param \Magento\Framework\Mail\EmailMessage $emailMessage
     */
    public function execute(EmailMessage $emailMessage) : void
    {
        $gmailService = $this->getGmailService->execute();

        foreach ($emailMessage->getFrom() as $address) {
            try {
                $result = $gmailService->users_settings_sendAs->get('me', $address->getEmail());
            } catch (Exception $exception) {
                throw new SenderVerificationException(
                    __(
                        'The email address %1 has not been configured: %2',
                        $address->getEmail(),
                        $exception->getMessage()
                    )
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
