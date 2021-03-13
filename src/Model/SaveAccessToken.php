<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Serialize\SerializerInterface;
use tr33m4n\GoogleOauthMail\Api\Data\TokenInterface;
use tr33m4n\GoogleOauthMail\Api\Data\TokenInterfaceFactory;
use tr33m4n\GoogleOauthMail\Exception\AccessTokenException;
use tr33m4n\GoogleOauthMail\Model\ResourceModel\Token as TokenResource;

/**
 * Class SaveAccessToken
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class SaveAccessToken
{
    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var \tr33m4n\GoogleOauthMail\Model\ValidateAccessToken
     */
    private $validateAccessToken;

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
     * @param \Magento\Framework\Api\DataObjectHelper                 $dataObjectHelper
     * @param \Magento\Framework\Serialize\SerializerInterface        $serializer
     * @param \tr33m4n\GoogleOauthMail\Model\ValidateAccessToken      $validateAccessToken
     * @param \tr33m4n\GoogleOauthMail\Api\Data\TokenInterfaceFactory $tokenFactory
     * @param \tr33m4n\GoogleOauthMail\Model\ResourceModel\Token      $tokenResource
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        SerializerInterface $serializer,
        ValidateAccessToken $validateAccessToken,
        TokenInterfaceFactory $tokenFactory,
        TokenResource $tokenResource
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->serializer = $serializer;
        $this->validateAccessToken = $validateAccessToken;
        $this->tokenFactory = $tokenFactory;
        $this->tokenResource = $tokenResource;
    }

    /**
     * Save access token
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \tr33m4n\GoogleOauthMail\Exception\AccessTokenException
     * @param array $accessToken
     */
    public function execute(array $accessToken) : void
    {
        $this->validateAccessToken->execute($accessToken);

        /** @var \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface $token */
        $token = $this->tokenFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $token,
            $accessToken,
            TokenInterface::class
        );

        /** @var \Magento\Framework\Model\AbstractModel $token */
        $this->tokenResource->save($token);
    }
}
