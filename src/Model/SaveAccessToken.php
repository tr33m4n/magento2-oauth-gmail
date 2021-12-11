<?php

namespace tr33m4n\OauthGmail\Model;

use Magento\Framework\Api\DataObjectHelper;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Api\Data\TokenInterfaceFactory;
use tr33m4n\OauthGmail\Model\ResourceModel\Token as TokenResource;

/**
 * Class SaveAccessToken
 *
 * @package tr33m4n\OauthGmail\Model
 */
class SaveAccessToken
{
    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \tr33m4n\OauthGmail\Model\ValidateAccessToken
     */
    private $validateAccessToken;

    /**
     * @var \tr33m4n\OauthGmail\Api\Data\TokenInterfaceFactory
     */
    private $tokenFactory;

    /**
     * @var \tr33m4n\OauthGmail\Model\ResourceModel\Token
     */
    private $tokenResource;

    /**
     * SaveAccessToken constructor.
     *
     * @param \Magento\Framework\Api\DataObjectHelper            $dataObjectHelper
     * @param \tr33m4n\OauthGmail\Model\ValidateAccessToken      $validateAccessToken
     * @param \tr33m4n\OauthGmail\Api\Data\TokenInterfaceFactory $tokenFactory
     * @param \tr33m4n\OauthGmail\Model\ResourceModel\Token      $tokenResource
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        ValidateAccessToken $validateAccessToken,
        TokenInterfaceFactory $tokenFactory,
        TokenResource $tokenResource
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->validateAccessToken = $validateAccessToken;
        $this->tokenFactory = $tokenFactory;
        $this->tokenResource = $tokenResource;
    }

    /**
     * Save access token
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \tr33m4n\OauthGmail\Exception\AccessTokenException
     * @param array $accessToken
     */
    public function execute(array $accessToken) : void
    {
        $this->validateAccessToken->execute($accessToken);

        /** @var \tr33m4n\OauthGmail\Api\Data\TokenInterface $token */
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
