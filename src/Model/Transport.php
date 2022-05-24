<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Exception;
use Google\Service\Gmail\Message;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;

class Transport implements TransportInterface
{
    private ValidateSender $validateSender;

    private GetGmailService $getGmailService;

    private MessageInterface $message;

    /**
     * Transport constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\ValidateSender  $validateSender
     * @param \tr33m4n\OauthGmail\Model\GetGmailService $getGmailService
     * @param \Magento\Framework\Mail\MessageInterface  $message
     */
    public function __construct(
        ValidateSender $validateSender,
        GetGmailService $getGmailService,
        MessageInterface $message
    ) {
        $this->validateSender = $validateSender;
        $this->getGmailService = $getGmailService;
        $this->message = $message;
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

            $googleMessage = new Message();
            // TODO: Elegantly handle message conversion
            $googleMessage->setRaw(strtr(base64_encode($emailMessage->getRawMessage()), ['+' => '-', '/' => '_']));

            $from = current((array) $emailMessage->getFrom());
            if (!$from) {
                throw new MailException(__('From field has not been specified'));
            }

            $this->getGmailService->execute()->users_messages->send($from->getEmail(), $googleMessage);
        } catch (Exception $exception) {
            throw new MailException(__($exception->getMessage()), $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getMessage() : MessageInterface
    {
        return $this->message;
    }
}
