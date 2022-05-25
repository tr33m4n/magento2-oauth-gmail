<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Exception;
use Google\Service\Gmail\Message;
use Google\Service\Gmail\MessageFactory;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;

class Transport implements TransportInterface
{
    private ValidateSender $validateSender;

    private GetGmailService $getGmailService;

    private MessageInterface $message;

    private MessageFactory $googleMessageFactory;

    /**
     * Transport constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\ValidateSender  $validateSender
     * @param \tr33m4n\OauthGmail\Model\GetGmailService $getGmailService
     * @param \Magento\Framework\Mail\MessageInterface  $message
     * @param \Google\Service\Gmail\MessageFactory      $googleMessageFactory
     */
    public function __construct(
        ValidateSender $validateSender,
        GetGmailService $getGmailService,
        MessageInterface $message,
        MessageFactory $googleMessageFactory
    ) {
        $this->validateSender = $validateSender;
        $this->getGmailService = $getGmailService;
        $this->message = $message;
        $this->googleMessageFactory = $googleMessageFactory;
    }

    /**
     * @inheritDoc
     */
    public function getMessage() : MessageInterface
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMessage() : void
    {
        /** @var \Magento\Framework\Mail\EmailMessage $emailMessage */
        $emailMessage = $this->getMessage();

        try {
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
     * @param \Magento\Framework\Mail\MessageInterface $message
     * @return \Google\Service\Gmail\Message
     */
    private function asGmailMessage(MessageInterface $message) : Message
    {
        /** @var \Google\Service\Gmail\Message $googleMessage */
        $googleMessage = $this->googleMessageFactory->create();
        $googleMessage->setRaw(strtr(base64_encode($emailMessage->getRawMessage()), ['+' => '-', '/' => '_']));

        return $googleMessage;
    }
}
