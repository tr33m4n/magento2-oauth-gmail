<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Magento\Framework\Serialize\SerializerInterface;
use tr33m4n\GoogleOauthMail\Api\Data\TokenInterfaceFactory;
use tr33m4n\GoogleOauthMail\Model\ResourceModel\Token as TokenResource;

/**
 * Class SaveAccessToken
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class SaveAccessToken
{
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var \tr33m4n\GoogleOauthMail\Api\Data\TokenInterfaceFactory
     */
    private $tokenFactory;

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\ResourceModel\Token
     */
    private $tokenResource;

    /**
     * SaveAccessToken constructor.
     *
     * @param \Magento\Framework\Serialize\SerializerInterface        $serializer
     * @param \tr33m4n\GoogleOauthMail\Api\Data\TokenInterfaceFactory $tokenFactory
     * @param \tr33m4n\GoogleOauthMail\Model\ResourceModel\Token      $tokenResource
     */
    public function __construct(
        SerializerInterface $serializer,
        TokenInterfaceFactory $tokenFactory,
        TokenResource $tokenResource
    ) {
        $this->serializer = $serializer;
        $this->tokenFactory = $tokenFactory;
        $this->tokenResource = $tokenResource;
    }

    /**
     * Save access token
     *
     * TODO: Set parts of token to DB columns
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @param array $accessToken
     */
    public function execute(array $accessToken) : void
    {
        /** @var \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface $token */
        $token = $this->tokenFactory->create();
        $token->setToken($this->serializer->serialize($accessToken));

        /** @var \Magento\Framework\Model\AbstractModel $token */
        $this->tokenResource->save($token);
    }
}
