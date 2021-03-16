<?php

namespace tr33m4n\OauthGoogleMail\Model;

use tr33m4n\OauthGoogleMail\Api\Data\TokenInterface;
use tr33m4n\OauthGoogleMail\Model\ResourceModel\Token\CollectionFactory;

/**
 * Class GetLatestAccessToken
 *
 * @package tr33m4n\OauthGoogleMail\Model
 */
class GetLatestAccessToken
{
    /**
     * @var \tr33m4n\OauthGoogleMail\Model\ResourceModel\Token\CollectionFactory
     */
    private $collectionFactory;

    /**
     * GetAccessToken constructor.
     *
     * @param \tr33m4n\OauthGoogleMail\Model\ResourceModel\Token\CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get latest access token
     *
     * @return \tr33m4n\OauthGoogleMail\Api\Data\TokenInterface|null
     */
    public function execute() : ?TokenInterface
    {
        /** @var \tr33m4n\OauthGoogleMail\Api\Data\TokenInterface $latestAccessToken */
        $latestAccessToken = $this->collectionFactory->create()
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder(TokenInterface::KEY_CREATED)
            ->getFirstItem();

        return null !== $latestAccessToken->getId()
            ? $latestAccessToken
            : null;
    }
}
