<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Exception;
use Google_Service_Gmail_Message;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Phrase;

class Transport implements TransportInterface
{
    private $getGmailService;

    private $message;

    public function __construct(
        GetGmailService $getGmailService,
        MessageInterface $message
    ) {
        $this->getGmailService = $getGmailService;
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function sendMessage()
    {
        try {
            $message = new Google_Service_Gmail_Message();
            $message->setRaw('Testing 1, 2, 3');

            $this->getGmailService->execute()->users_messages->send(
                'me',
                $message
            );
        } catch (Exception $exception) {
            throw new MailException(new Phrase($exception->getMessage()), $exception);
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
