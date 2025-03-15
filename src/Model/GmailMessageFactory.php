<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Google\Service\Gmail\Message;
use Google\Service\Gmail\MessageFactory;
use Magento\Framework\Mail\MailMessageInterface;

class GmailMessageFactory
{
    /**
     * GmailMessageFactory constructor.
     */
    public function __construct(
        private readonly MessageFactory $googleMessageFactory
    ) {
    }

    /**
     * Create Gmail message
     */
    public function create(MailMessageInterface $message): Message
    {
        /** @var \Google\Service\Gmail\Message $googleMessage */
        $googleMessage = $this->googleMessageFactory->create();
        $googleMessage->setRaw(strtr(base64_encode($message->getRawMessage()), ['+' => '-', '/' => '_']));

        return $googleMessage;
    }
}
