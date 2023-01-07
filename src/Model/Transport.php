<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Exception;
use Google\Service\Gmail\Message;
use Google\Service\Gmail\MessageFactory;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MailMessageInterface;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;

class Transport implements TransportInterface
{
    private ValidateSender $validateSender;

    private ApplyImpersonatedEmails $applyImpersonatedEmails;

    private GetGmailService $getGmailService;

    private MessageInterface $message;

    private MessageFactory $googleMessageFactory;

    /**
     * Transport constructor.
     */
    public function __construct(
        ValidateSender $validateSender,
        ApplyImpersonatedEmails $applyImpersonatedEmails,
        GetGmailService $getGmailService,
        MessageInterface $message,
        MessageFactory $googleMessageFactory
    ) {
        $this->validateSender = $validateSender;
        $this->applyImpersonatedEmails = $applyImpersonatedEmails;
        $this->getGmailService = $getGmailService;
        $this->message = $message;
        $this->googleMessageFactory = $googleMessageFactory;
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
            $this->applyImpersonatedEmails->execute($emailMessage, $this->getGmailService->execute()->getClient());
            $this->validateSender->execute($emailMessage);

            $this->getGmailService->execute()
                ->users_messages
                ->send('me', $this->asGmailMessage($emailMessage));
        } catch (Exception $exception) {
            throw new MailException(__($exception->getMessage()), $exception);
        }
    }

    /**
     * As Gmail message
     *
     * @param \Magento\Framework\Mail\MailMessageInterface $message
     * @return \Google\Service\Gmail\Message
     */
    private function asGmailMessage(MailMessageInterface $message): Message
    {
        /** @var \Google\Service\Gmail\Message $googleMessage */
        $googleMessage = $this->googleMessageFactory->create();
        $googleMessage->setRaw(strtr(base64_encode($message->getRawMessage()), ['+' => '-', '/' => '_']));

        return $googleMessage;
    }
}
