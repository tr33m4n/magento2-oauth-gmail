<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Exception;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;
use tr33m4n\OauthGmail\Api\GmailClientInterface;

class Transport implements TransportInterface
{
    /**
     * Transport constructor.
     */
    public function __construct(
        private readonly ValidateSender $validateSender,
        private readonly DelegateEmails $delegateEmails,
        private readonly GmailClientInterface $gmailClient,
        private readonly MessageInterface $message,
        private readonly GmailMessageFactory $gmailMessageFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMessage(): void
    {
        /** @var \Magento\Framework\Mail\EmailMessage $emailMessage */
        $emailMessage = $this->getMessage();

        try {
            $this->delegateEmails->execute($emailMessage);
            $this->validateSender->execute($emailMessage);

            $this->gmailClient->getUsersMessages()
                ->send('me', $this->gmailMessageFactory->create($emailMessage));
        } catch (Exception $exception) {
            throw new MailException(__($exception->getMessage()), $exception);
        }
    }
}
