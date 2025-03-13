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
    /**
     * Transport constructor.
     */
    public function __construct(
        private readonly ValidateSender $validateSender,
        private readonly ApplyImpersonatedEmails $applyImpersonatedEmails,
        private readonly GetGmailService $getGmailService,
        private readonly MessageInterface $message,
        private readonly MessageFactory $googleMessageFactory
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
            $this->applyImpersonatedEmails->execute($emailMessage, $this->getGmailService->execute()->getClient());
            $this->validateSender->execute($emailMessage);

            // @phpstan-ignore-next-line
            $this->getGmailService->execute()
                ->users_messages
                ->send('me', $this->asGmailMessage($emailMessage));
        } catch (Exception $exception) {
            throw new MailException(__($exception->getMessage()), $exception);
        }
    }

    /**
     * As Gmail message
     */
    private function asGmailMessage(MailMessageInterface $message): Message
    {
        /** @var \Google\Service\Gmail\Message $googleMessage */
        $googleMessage = $this->googleMessageFactory->create();
        $googleMessage->setRaw(strtr(base64_encode($message->getRawMessage()), ['+' => '-', '/' => '_']));

        return $googleMessage;
    }
}
