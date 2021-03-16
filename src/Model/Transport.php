<?php

namespace tr33m4n\OauthGoogleMail\Model;

use Exception;
use Google_Service_Gmail_Message;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Phrase;

/**
 * Class Transport
 *
 * @package tr33m4n\OauthGoogleMail\Model
 */
class Transport implements TransportInterface
{
    /**
     * @var \tr33m4n\OauthGoogleMail\Model\ValidateSender
     */
    private $validateSender;

    /**
     * @var \tr33m4n\OauthGoogleMail\Model\GetGmailService
     */
    private $getGmailService;

    /**
     * @var \Magento\Framework\Mail\MessageInterface
     */
    private $message;

    /**
     * Transport constructor.
     *
     * @param \tr33m4n\OauthGoogleMail\Model\ValidateSender  $validateSender
     * @param \tr33m4n\OauthGoogleMail\Model\GetGmailService $getGmailService
     * @param \Magento\Framework\Mail\MessageInterface       $message
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
    public function sendMessage()
    {
        /** @var \Magento\Framework\Mail\EmailMessage $emailMessage */
        $emailMessage = $this->getMessage();

        try {
            $this->validateSender->execute($emailMessage);

            $googleMessage = new Google_Service_Gmail_Message();
            // TODO: Elegantly handle message conversion
            $googleMessage->setRaw(strtr(base64_encode($emailMessage->getRawMessage()), ['+' => '-', '/' => '_']));

            $this->getGmailService->execute()
                ->users_messages->send(
                    current($emailMessage->getFrom())->getEmail(),
                    $googleMessage
                );
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
